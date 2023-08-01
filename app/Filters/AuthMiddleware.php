<?php
// app/Filters/AuthMiddleware.php
namespace App\Filters;

use Firebase\JWT\JWT;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class AuthMiddleware implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getServer('HTTP_AUTHORIZATION');
        if (!$authHeader) {
            return Services::response()
                ->setStatusCode(401)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Token not provided'
                ]);
        }

        // Ambil token dari header Authorization
        $token = explode(' ', $authHeader)[1];

        // Verifikasi token 
        try {
            // $jwtSecretKey = 'rahasia'; // Ganti dengan secret key Anda
            $jwtSecretKey = getenv('TOKEN_SECRET');
            $algorithm = "HS256";
            $headers = null;
            // $decodedToken = JWT::decode($token, $jwtSecretKey, $headers, $algorithm);
            $decodedToken = JWT::decode($token, $jwtSecretKey,  $algorithm);
            // JWT::decode($token, $key, ['HS256']);
        } catch (\Exception $e) {
            return Services::response()
                ->setStatusCode(401)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid token'
                ]);
        }

        // Simpan data user autentikasi ke dalam request untuk digunakan di Controller
        $request->user = $decodedToken->data;

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
