<?php

namespace Whozidis\HallOfFame\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Support\Facades\Crypt;

class PgpKey extends BaseModel
{
    protected $table = 'hof_pgp_keys';

    protected $fillable = [
        'key_name',
        'key_id',
        'public_key',
        'private_key',
        'key_password',
        'key_email',
        'key_fingerprint',
        'expires_at',
        'is_active',
        'can_sign',
        'can_encrypt',
        'key_info',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'is_active' => 'boolean',
        'can_sign' => 'boolean',
        'can_encrypt' => 'boolean',
        'key_info' => 'array',
    ];

    protected $hidden = [
        'private_key',
        'key_password',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'expires_at',
    ];

    /**
     * Encrypt the private key before saving
     */
    public function setPrivateKeyAttribute($value): void
    {
        if ($value) {
            $this->attributes['private_key'] = Crypt::encryptString($value);
        }
    }

    /**
     * Decrypt the private key when accessing
     */
    public function getPrivateKeyAttribute($value): ?string
    {
        if ($value) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * Encrypt the key password before saving
     */
    public function setKeyPasswordAttribute($value): void
    {
        if ($value) {
            $this->attributes['key_password'] = Crypt::encryptString($value);
        }
    }

    /**
     * Decrypt the key password when accessing
     */
    public function getKeyPasswordAttribute($value): ?string
    {
        if ($value) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * Get the active signing key
     */
    public static function getActiveSigningKey(): ?self
    {
        return self::where('is_active', true)
            ->where('can_sign', true)
            ->whereNotNull('private_key')
            ->first();
    }

    /**
     * Get the active encryption key
     */
    public static function getActiveEncryptionKey(): ?self
    {
        return self::where('is_active', true)
            ->where('can_encrypt', true)
            ->first();
    }

    /**
     * Check if key is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get key status badge
     */
    public function getStatusBadge(): string
    {
        if (!$this->is_active) {
            return '<span class="badge bg-secondary">Inactive</span>';
        }
        
        if ($this->isExpired()) {
            return '<span class="badge bg-danger">Expired</span>';
        }
        
        return '<span class="badge bg-success">Active</span>';
    }
}
