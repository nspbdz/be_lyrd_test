<?php

namespace App\Controllers;
use Firebase\JWT\JWT;
use App\Models\User;
use CodeIgniter\API\ResponseTrait;

class AuthController extends BaseController
{
    use ResponseTrait;

  
    public function login()
    {
        $request = service('request');
        $email = $request->getPost('email');
        $password = $request->getPost('password');
        // dd($email);
        // Validasi email dan password (gunakan validation rules sesuai kebutuhan)
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            $response = [
                'status' => 'error',
                'message' => 'Invalid email or password',
                'errors' => $this->validator->getErrors(),
            ];
            return $this->respond($response, 400);
        }

        // Lakukan proses autentikasi dengan memeriksa email dan password di basis data (users table)
        $User = new User();
        $user = $User->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            $response = [
                'status' => 'error',
                'message' => 'Invalid email or password',
            ];
            return $this->respond($response, 401);
        }

        // Autentikasi berhasil, berikan token JWT (contoh sederhana, gunakan pustaka JWT yang sesuai untuk implementasi yang lebih aman)
        // dd('aaa');
        $key = "rahasia";
        $algorithm = "HS256";
        $jwtToken = JWT::encode(['user_id' => $user['id'], 'email' => $user['email']],$key, $algorithm);

        $response = [
            'status' => 'success',
            'message' => 'Login success',
            'token' => $jwtToken,
            'user' => $user, // Anda bisa menyertakan data user lainnya jika diperlukan
        ];

        return $this->respond($response);
    }
  
}
