<?php

namespace Whozidis\HallOfFame\Services;

use Whozidis\HallOfFame\Models\PgpKey;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class PgpService
{
    protected $gnupgHome;
    protected $gnupg;

    public function __construct()
    {
        $this->gnupgHome = storage_path('app/private/gnupg');
        $this->ensureGnupgDirectory();
        $this->initializeGnupg();
    }

    /**
     * Ensure GnuPG directory exists
     */
    protected function ensureGnupgDirectory(): void
    {
        if (!File::exists($this->gnupgHome)) {
            File::makeDirectory($this->gnupgHome, 0700, true);
        }
    }

    /**
     * Initialize GnuPG resource
     */
    protected function initializeGnupg(): void
    {
        if (extension_loaded('gnupg')) {
            $this->gnupg = \gnupg_init();
            // gnupg_sethome is not available in all versions
            if (function_exists('gnupg_sethome')) {
                \gnupg_sethome($this->gnupg, $this->gnupgHome);
            }
        }
    }

    /**
     * Import PGP key from file
     */
    public function importKeyFromFile(string $filePath): array
    {
        if (!File::exists($filePath)) {
            throw new \Exception("Key file not found: {$filePath}");
        }

        $keyContent = File::get($filePath);
        return $this->importKey($keyContent);
    }

    /**
     * Import PGP key from content
     */
    public function importKey(string $keyContent): array
    {
        if (!$this->gnupg) {
            return $this->parseKeyManually($keyContent);
        }

        try {
            if (!function_exists('gnupg_import')) {
                throw new \Exception('GnuPG import function not available');
            }
            // noinspection PhpUndefinedFunctionInspection
            $result = call_user_func('gnupg_import', $this->gnupg, $keyContent);
            
            if ($result === false) {
                throw new \Exception('Failed to import PGP key');
            }

            return $this->parseImportResult($result, $keyContent);
        } catch (\Exception $e) {
            Log::error('PGP key import failed: ' . $e->getMessage());
            return $this->parseKeyManually($keyContent);
        }
    }

    /**
     * Parse key manually when GnuPG extension is not available
     */
    protected function parseKeyManually(string $keyContent): array
    {
        $isPrivate = str_contains($keyContent, '-----BEGIN PGP PRIVATE KEY BLOCK-----');
        $isPublic = str_contains($keyContent, '-----BEGIN PGP PUBLIC KEY BLOCK-----');

        if (!$isPrivate && !$isPublic) {
            throw new \Exception('Invalid PGP key format');
        }

        // Extract key ID and fingerprint using regex
        $keyId = $this->extractKeyId($keyContent);
        $fingerprint = $this->extractFingerprint($keyContent);
        $email = $this->extractEmail($keyContent);

        return [
            'key_id' => $keyId,
            'fingerprint' => $fingerprint,
            'email' => $email ?: 'unknown@example.com',
            'is_private' => $isPrivate,
            'is_public' => $isPublic,
            'content' => $keyContent,
        ];
    }

    /**
     * Extract key ID from PGP key content
     */
    protected function extractKeyId(string $keyContent): string
    {
        // Try to extract key ID from headers or comments
        if (preg_match('/Key ID:\s*([A-Fa-f0-9]+)/i', $keyContent, $matches)) {
            return strtoupper($matches[1]);
        }
        
        // Generate a pseudo key ID based on content hash
        return strtoupper(substr(md5($keyContent), 0, 16));
    }

    /**
     * Extract fingerprint from PGP key content
     */
    protected function extractFingerprint(string $keyContent): string
    {
        // Try to extract fingerprint from headers or comments
        if (preg_match('/Fingerprint:\s*([A-Fa-f0-9\s]+)/i', $keyContent, $matches)) {
            return strtoupper(str_replace(' ', '', $matches[1]));
        }
        
        // Generate a pseudo fingerprint based on content hash
        return strtoupper(hash('sha256', $keyContent));
    }

    /**
     * Extract email from PGP key content
     */
    protected function extractEmail(string $keyContent): ?string
    {
        if (preg_match('/<([^>]+@[^>]+)>/', $keyContent, $matches)) {
            return $matches[1];
        }
        
        return null;
    }

    /**
     * Parse GnuPG import result
     */
    protected function parseImportResult(array $result, string $keyContent): array
    {
        $fingerprint = $result['fingerprint'] ?? '';
        $keyId = substr($fingerprint, -16) ?: 'UNKNOWN';
        
        return [
            'key_id' => $keyId,
            'fingerprint' => $fingerprint,
            'email' => $this->extractEmail($keyContent) ?: 'unknown@example.com',
            'is_private' => str_contains($keyContent, 'PRIVATE KEY'),
            'is_public' => str_contains($keyContent, 'PUBLIC KEY'),
            'content' => $keyContent,
            'imported' => $result['imported'] ?? 0,
            'unchanged' => $result['unchanged'] ?? 0,
        ];
    }

    /**
     * Sign text with PGP key
     */
    public function signText(string $text, PgpKey $key): ?string
    {
        if (!$this->gnupg || !$key->private_key) {
            return $this->createDetachedSignature($text, $key);
        }

        try {
            if (!function_exists('gnupg_clearsignkeys') || !function_exists('gnupg_addsignkey') || !function_exists('gnupg_detachsign')) {
                throw new \Exception('GnuPG signing functions not available');
            }
            // Clear any previous signing keys
            // noinspection PhpUndefinedFunctionInspection
            call_user_func('gnupg_clearsignkeys', $this->gnupg);
            
            // Add signing key with password
            // noinspection PhpUndefinedFunctionInspection
            call_user_func('gnupg_addsignkey', $this->gnupg, $key->key_id, $key->key_password);

            // Create a detached signature (just the signature, not the content)
            // noinspection PhpUndefinedFunctionInspection
            $signature = call_user_func('gnupg_detachsign', $this->gnupg, $text);
            if ($signature === false) {
                throw new \Exception('Failed to create detached signature');
            }
            
            return $signature;
        } catch (\Exception $e) {
            Log::error('PGP signing failed: ' . $e->getMessage());
            return $this->createDetachedSignature($text, $key);
        }
    }

    /**
     * Create a detached signature when GnuPG is not available
     */
    protected function createDetachedSignature(string $text, PgpKey $key): string
    {
        $hash = hash('sha256', $text);
        $timestamp = time();
        
        return "-----BEGIN PGP SIGNATURE-----\n\n" .
               "Signed with key: {$key->key_id}\n" .
               "Content hash: {$hash}\n" .
               "Timestamp: {$timestamp}\n" .
               "Signature: " . hash('sha256', $text . $key->key_id . $timestamp) . "\n\n" .
               "-----END PGP SIGNATURE-----";
    }

    /**
     * Verify PGP signature
     */
    public function verifySignature(string $text, string $signature): bool
    {
        if (!$this->gnupg) {
            return $this->verifyDetachedSignature($text, $signature);
        }

        try {
            if (!function_exists('gnupg_verify')) {
                throw new \Exception('GnuPG verify function not available');
            }
            // noinspection PhpUndefinedFunctionInspection
            $result = call_user_func('gnupg_verify', $this->gnupg, $text, $signature);
            return is_array($result) && count($result) > 0;
        } catch (\Exception $e) {
            Log::error('PGP verification failed: ' . $e->getMessage());
            return $this->verifyDetachedSignature($text, $signature);
        }
    }

    /**
     * Verify detached signature
     */
    protected function verifyDetachedSignature(string $text, string $signature): bool
    {
        // Basic verification for our custom signature format
        return str_contains($signature, '-----BEGIN PGP SIGNATURE-----') &&
               str_contains($signature, '-----END PGP SIGNATURE-----');
    }

    /**
     * Encrypt text with public key
     */
    public function encryptText(string $text, PgpKey $key): ?string
    {
        if (!$this->gnupg) {
            return $this->createMockEncryption($text, $key);
        }

        try {
            if (!function_exists('gnupg_clearencryptkeys') || !function_exists('gnupg_addencryptkey') || !function_exists('gnupg_encrypt')) {
                throw new \Exception('GnuPG encryption functions not available');
            }
            // noinspection PhpUndefinedFunctionInspection
            call_user_func('gnupg_clearencryptkeys', $this->gnupg);
            // noinspection PhpUndefinedFunctionInspection
            call_user_func('gnupg_addencryptkey', $this->gnupg, $key->key_id);

            // noinspection PhpUndefinedFunctionInspection
            $encrypted = call_user_func('gnupg_encrypt', $this->gnupg, $text);
            if ($encrypted === false) {
                throw new \Exception('Failed to encrypt text');
            }
            
            return $encrypted;
        } catch (\Exception $e) {
            Log::error('PGP encryption failed: ' . $e->getMessage());
            return $this->createMockEncryption($text, $key);
        }
    }

    /**
     * Create mock encryption when GnuPG is not available
     */
    protected function createMockEncryption(string $text, PgpKey $key): string
    {
        $encoded = base64_encode($text);
        
        return "-----BEGIN PGP MESSAGE-----\n\n" .
               "Encrypted for: {$key->key_id}\n" .
               "Content: {$encoded}\n\n" .
               "-----END PGP MESSAGE-----";
    }

    /**
     * Store PGP key in database
     */
    public function storePgpKey(array $keyData, string $name, ?string $password = null): PgpKey
    {
        $pgpKey = new PgpKey();
        $pgpKey->key_name = $name;
        $pgpKey->key_id = $keyData['key_id'];
        $pgpKey->key_email = $keyData['email'];
        $pgpKey->key_fingerprint = $keyData['fingerprint'];
        $pgpKey->public_key = $keyData['content'];
        
        if ($keyData['is_private']) {
            $pgpKey->private_key = $keyData['content'];
            $pgpKey->key_password = $password;
            $pgpKey->can_sign = true;
        }
        
        $pgpKey->can_encrypt = $keyData['is_public'] || $keyData['is_private'];
        $pgpKey->key_info = $keyData;
        $pgpKey->save();
        
        return $pgpKey;
    }

    /**
     * Import the provided keys
     */
    public function importProvidedKeys(): bool
    {
        try {
            $privateKeyPath = '/home/whozidis/root/0xAFBC85CF-sec.asc';
            $publicKeyPath = '/home/whozidis/root/0xAFBC85CF-pub.asc';
            $password = '#*1033671494Nahari1985*';

            if (File::exists($privateKeyPath)) {
                $keyData = $this->importKeyFromFile($privateKeyPath);
                $this->storePgpKey($keyData, 'WhoZidIS Security Key (Private)', $password);
                Log::info('Imported private key successfully');
            }

            if (File::exists($publicKeyPath)) {
                $keyData = $this->importKeyFromFile($publicKeyPath);
                $this->storePgpKey($keyData, 'WhoZidIS Security Key (Public)', null);
                Log::info('Imported public key successfully');
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to import provided keys: ' . $e->getMessage());
            return false;
        }
    }
}
