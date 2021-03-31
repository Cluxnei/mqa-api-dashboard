<?php


namespace App\Services;


use Exception;

class AuthService
{
    /**
     * @return string
     * @throws Exception
     */
    public static function generateVerificationCode(): string
    {
        $code = '';
        for ($i = 0; $i < 4; $i++) {
            $code .= random_int(0, 9);
        }
        return $code;
    }
}