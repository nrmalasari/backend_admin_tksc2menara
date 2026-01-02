<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistPendaftar extends Model
{
    use HasFactory;

    protected $table = 'regist_pendaftars';

    protected $fillable = [
        'username',
        'email',
        'password',
        'encrypted_password',
        'encrypted_data',
        'registration_ip',
        'user_agent'
    ];

    protected $hidden = [
        'password',
        'encrypted_password'
    ];

    protected $appends = ['decrypted_password'];

    // Kunci dan IV untuk AES
    private function getEncryptionKey()
    {
        // Gunakan APP_KEY dari Laravel, pastikan panjang 32 karakter
        return substr(hash('sha256', config('app.key')), 0, 32);
    }

    private function getEncryptionIV()
    {
        // IV tetap, panjang 16 karakter untuk AES-256-CBC
        return '0123456789abcdef'; // 16 karakter
    }

    /**
     * Encrypt password dengan AES-256-CBC sebelum save
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $encrypted = $this->encryptAES($value);
            $this->attributes['password'] = $encrypted;
            $this->attributes['encrypted_password'] = $encrypted; // Backup
        }
    }

    /**
     * Get decrypted password
     */
    public function getDecryptedPasswordAttribute()
    {
        if (isset($this->attributes['password']) && !empty($this->attributes['password'])) {
            try {
                return $this->decryptAES($this->attributes['password']);
            } catch (\Exception $e) {
                \Log::error('Decryption error: ' . $e->getMessage());
                return 'Error: Cannot decrypt';
            }
        }
        return null;
    }

    /**
     * Get encrypted_data
     */
    public function getEncryptedDataAttribute($value)
    {
        if ($value) {
            try {
                $decrypted = $this->decryptAES($value);
                // Coba decode JSON, jika bukan JSON return string
                $data = json_decode($decrypted, true);
                return $data ?? $decrypted;
            } catch (\Exception $e) {
                \Log::error('Encrypted data decryption error: ' . $e->getMessage());
                return 'Error: Cannot decrypt data';
            }
        }
        return null;
    }

    /**
     * Set encrypted_data
     */
    public function setEncryptedDataAttribute($value)
    {
        if ($value) {
            $data = is_array($value) ? json_encode($value) : $value;
            $this->attributes['encrypted_data'] = $this->encryptAES($data);
        } else {
            $this->attributes['encrypted_data'] = null;
        }
    }

    /**
     * Encrypt dengan AES-256-CBC
     */
    public function encryptAES($data)
    {
        $key = $this->getEncryptionKey();
        $iv = $this->getEncryptionIV();
        
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        if ($encrypted === false) {
            throw new \Exception('Encryption failed: ' . openssl_error_string());
        }
        return base64_encode($encrypted);
    }

    /**
     * Decrypt AES-256-CBC
     */
    public function decryptAES($data)
    {
        $key = $this->getEncryptionKey();
        $iv = $this->getEncryptionIV();
        
        $decrypted = openssl_decrypt(base64_decode($data), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        if ($decrypted === false) {
            throw new \Exception('Decryption failed: ' . openssl_error_string());
        }
        return $decrypted;
    }

    /**
     * Verify password (untuk login)
     */
    public function verifyPassword($password)
    {
        try {
            $decryptedPassword = $this->decryptAES($this->attributes['password'] ?? '');
            return hash_equals($decryptedPassword, $password); // Gunakan hash_equals untuk timing attack prevention
        } catch (\Exception $e) {
            \Log::error('Password verification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Scope untuk mencari by username atau email
     */
    public function scopeFindByUsernameOrEmail($query, $identifier)
    {
        return $query->where('username', $identifier)
                    ->orWhere('email', $identifier);
    }
}