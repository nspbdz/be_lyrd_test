<?php

use \Firebase\JWT\JWT;

if (!function_exists('generateJwtToken')) {
    function generateJwtToken($data)
    {
        $key = 'rahasia'; // Ganti dengan secret key yang sesuai
        $algorithm = "HS256"; // Contoh menggunakan algoritma HS256
        $token = JWT::encode($data, $key, $algorithm);

        return $token;
    }
}

if (!function_exists('decodeJwtToken')) {
    function decodeJwtToken($token)
    {
        $key = 'rahasia'; // Ganti dengan secret key yang sesuai
        $allowedAlgorithms = null; // Gunakan array untuk daftar algoritma

        try {
            $decoded = JWT::decode($token, $key, $allowedAlgorithms);
            return (array) $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }
}
