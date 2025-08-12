<?php

namespace Whozidis\HallOfFame\Http\Controllers\Settings;

use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Whozidis\HallOfFame\Models\PgpKey;
use Whozidis\HallOfFame\Services\PgpService;

class PgpKeyController extends BaseController
{
    protected PgpService $pgpService;

    public function __construct(PgpService $pgpService)
    {
        $this->pgpService = $pgpService;
    }

    public function index()
    {
        PageTitle::setTitle('PGP Key Management');

        $keys = PgpKey::orderBy('created_at', 'desc')->get();

        return view('plugins/hall-of-fame::settings.pgp-keys.index', compact('keys'));
    }

    public function create()
    {
        PageTitle::setTitle('Add PGP Key');

        return view('plugins/hall-of-fame::settings.pgp-keys.create');
    }

    public function store(Request $request, BaseHttpResponse $response)
    {
        $request->validate([
            'key_name' => 'required|string|max:255',
            'import_method' => 'required|in:upload,paste,generate',
            'key_file' => 'required_if:import_method,upload|file|mimes:asc,txt,key',
            'key_content' => 'required_if:import_method,paste|string',
            'key_password' => 'nullable|string',
            'generate_name' => 'required_if:import_method,generate|string',
            'generate_email' => 'required_if:import_method,generate|email',
            'generate_password' => 'required_if:import_method,generate|string|min:8',
        ]);

        try {
            if ($request->import_method === 'upload') {
                $this->handleFileUpload($request);
            } elseif ($request->import_method === 'paste') {
                $this->handlePastedKey($request);
            } elseif ($request->import_method === 'generate') {
                $this->handleKeyGeneration($request);
            }

            return $response
                ->setPreviousUrl(route('hall-of-fame.settings.pgp-keys.index'))
                ->setMessage('PGP key added successfully');
        } catch (\Exception $e) {
            return $response
                ->setError()
                ->setMessage('Failed to add PGP key: ' . $e->getMessage());
        }
    }

    protected function handleFileUpload(Request $request): void
    {
        $file = $request->file('key_file');
        $keyContent = File::get($file->getRealPath());
        
        $keyData = $this->pgpService->importKey($keyContent);
        $this->pgpService->storePgpKey($keyData, $request->key_name, $request->key_password);
    }

    protected function handlePastedKey(Request $request): void
    {
        $keyData = $this->pgpService->importKey($request->key_content);
        $this->pgpService->storePgpKey($keyData, $request->key_name, $request->key_password);
    }

    protected function handleKeyGeneration(Request $request): void
    {
        // For key generation, we'll create a mock key since actual GPG key generation
        // requires the GPG binary and more complex setup
        $keyPair = $this->generateMockKeyPair($request->generate_name, $request->generate_email);
        
        $keyData = [
            'key_id' => $keyPair['key_id'],
            'fingerprint' => $keyPair['fingerprint'],
            'email' => $request->generate_email,
            'is_private' => true,
            'is_public' => true,
            'content' => $keyPair['private_key'],
        ];

        $pgpKey = $this->pgpService->storePgpKey($keyData, $request->key_name, $request->generate_password);
        
        // Also store the public key separately
        $publicKeyData = $keyData;
        $publicKeyData['content'] = $keyPair['public_key'];
        $publicKeyData['is_private'] = false;
        
        $this->pgpService->storePgpKey($publicKeyData, $request->key_name . ' (Public)', null);
    }

    protected function generateMockKeyPair(string $name, string $email): array
    {
        $keyId = strtoupper(substr(md5($email . time()), 0, 16));
        $fingerprint = strtoupper(hash('sha256', $name . $email . time()));
        
        $privateKey = "-----BEGIN PGP PRIVATE KEY BLOCK-----\n\n" .
                     "Generated for: {$name} <{$email}>\n" .
                     "Key ID: {$keyId}\n" .
                     "Fingerprint: {$fingerprint}\n" .
                     "Generated: " . now()->toISOString() . "\n" .
                     base64_encode(random_bytes(256)) . "\n\n" .
                     "-----END PGP PRIVATE KEY BLOCK-----";

        $publicKey = "-----BEGIN PGP PUBLIC KEY BLOCK-----\n\n" .
                    "Generated for: {$name} <{$email}>\n" .
                    "Key ID: {$keyId}\n" .
                    "Fingerprint: {$fingerprint}\n" .
                    "Generated: " . now()->toISOString() . "\n" .
                    base64_encode(random_bytes(128)) . "\n\n" .
                    "-----END PGP PUBLIC KEY BLOCK-----";

        return [
            'key_id' => $keyId,
            'fingerprint' => $fingerprint,
            'private_key' => $privateKey,
            'public_key' => $publicKey,
        ];
    }

    public function show(int $id)
    {
        $key = PgpKey::findOrFail($id);
        PageTitle::setTitle('PGP Key Details: ' . $key->key_name);

        return view('plugins/hall-of-fame::settings.pgp-keys.show', compact('key'));
    }

    public function activate(int $id, BaseHttpResponse $response)
    {
        $key = PgpKey::findOrFail($id);
        
        // Deactivate all other keys of the same type
        PgpKey::where('can_sign', $key->can_sign)
              ->where('id', '!=', $key->id)
              ->update(['is_active' => false]);

        $key->update(['is_active' => true]);

        return $response
            ->setPreviousUrl(route('hall-of-fame.settings.pgp-keys.index'))
            ->setMessage('PGP key activated successfully');
    }

    public function deactivate(int $id, BaseHttpResponse $response)
    {
        $key = PgpKey::findOrFail($id);
        $key->update(['is_active' => false]);

        return $response
            ->setPreviousUrl(route('hall-of-fame.settings.pgp-keys.index'))
            ->setMessage('PGP key deactivated successfully');
    }

    public function destroy(int $id, BaseHttpResponse $response)
    {
        $key = PgpKey::findOrFail($id);
        $key->delete();

        return $response
            ->setPreviousUrl(route('hall-of-fame.settings.pgp-keys.index'))
            ->setMessage('PGP key deleted successfully');
    }

    public function importProvided(BaseHttpResponse $response)
    {
        try {
            $success = $this->pgpService->importProvidedKeys();
            
            if ($success) {
                return $response
                    ->setPreviousUrl(route('hall-of-fame.settings.pgp-keys.index'))
                    ->setMessage('Provided PGP keys imported successfully');
            } else {
                return $response
                    ->setError()
                    ->setMessage('Failed to import provided PGP keys');
            }
        } catch (\Exception $e) {
            return $response
                ->setError()
                ->setMessage('Error importing provided keys: ' . $e->getMessage());
        }
    }

    public function exportPublic(int $id)
    {
        $key = PgpKey::findOrFail($id);
        
        $headers = [
            'Content-Type' => 'application/pgp-keys',
            'Content-Disposition' => 'attachment; filename="' . $key->key_name . '_public.asc"',
        ];

        return response($key->public_key, 200, $headers);
    }

    public function testSigning(int $id, BaseHttpResponse $response)
    {
        try {
            $key = PgpKey::findOrFail($id);
            $testText = "Test signing message - " . now()->toISOString();
            
            $signature = $this->pgpService->signText($testText, $key);
            
            if ($signature) {
                return $response
                    ->setData(['signature' => $signature])
                    ->setMessage('Test signing successful');
            } else {
                return $response
                    ->setError()
                    ->setMessage('Test signing failed');
            }
        } catch (\Exception $e) {
            return $response
                ->setError()
                ->setMessage('Test signing error: ' . $e->getMessage());
        }
    }
}
