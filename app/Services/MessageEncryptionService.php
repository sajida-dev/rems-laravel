<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class MessageEncryptionService
{
    public function encrypt(string $message): string
    {
        return Crypt::encryptString($message);
    }

    public function decrypt(string $encryptedMessage): string
    {
        return Crypt::decryptString($encryptedMessage);
    }
}
