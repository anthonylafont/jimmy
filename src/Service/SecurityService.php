<?php

namespace App\Service;

class SecurityService
{
    public const RANDOM_PASSWORD_LENGTH = 15;
    public const PASSWORD_TOKEN_LENGTH = 30;

    public const PASSWORD_TOKEN_VALIDITY = 'P1D';


    public static function generateRandomString(int $length): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
