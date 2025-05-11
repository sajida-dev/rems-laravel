<?php

namespace App\Helpers;

use App\Models\User;

class UserHelper
{
    public static function generateUniqueUsername($name, $email)
    {
        $baseUsername = strtolower(preg_replace('/\s+/', '', $name));
        $username = $baseUsername;

        $exists = User::where('username', $username)->exists();

        if ($exists) {
            $counter = 1;
            do {
                $username = $baseUsername . $counter;
                $exists = User::where('username', $username)->exists();
                $counter++;
            } while ($exists);
        }

        return $username;
    }

    public static function generateRandomPassword($length = 12)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*';
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $password;
    }
}
