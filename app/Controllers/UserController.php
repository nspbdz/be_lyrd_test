<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;

class UserController extends BaseController
{
    public function index()
    {
        $User = new User();
        $Users = $User->findAll();

        // Mengirimkan respons JSON
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $Users,
        ]);
    }

    public function show($id)
    {
        $User = new User();
        $User = $User->find($id);

        if (!$User) {
            $response = [
                'status' => 'error',
                'message' => 'User not found',
            ];
            return $this->response->setJSON($response)->setStatusCode(400);
        }

        $response = [
            'status' => 'success',
            'message' => 'User found',
            'data' => $User,
        ];

        return $this->response->setJSON($response);
    }

    public function create()
    {
        $request = service('request');

        // Validasi input data users
        $validationRules = [
            'name' => 'required',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ];

        if (!$this->validate($validationRules)) {
            $response = [
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors(),
            ];

            return $this->response->setJSON($response)->setStatusCode(400);
        }

        // Jika validasi berhasil, simpan data users ke database
        $User = new User();

        $data = [
            'name' => $request->getPost('name'),
            'email' => $request->getPost('email'),
            'password' => password_hash($request->getVar('password'), PASSWORD_DEFAULT),
        ];

        // Insert data users ke database
        $User->insert($data);

        // Berikan respons sukses
        $response = [
            'status' => 'success',
            'message' => 'User created successfully',
        ];

        return $this->response->setJSON($response);
    }

    public function edit($id)
    {
        $request = service('request');
        // dd($request->getVar('name'));

        // Proses update data user
        $userModel = new User();
        $user = $userModel->find($id);

        if (!$user) {
            $response = [
                'status' => 'error',
                'message' => 'User not found',
            ];
            return $this->response->setJSON($response)->setStatusCode(400);
        }

        $data = [
            'name' => $request->getVar('name'),
            'email' => $request->getVar('email'),
            'password' => password_hash($request->getVar('password'), PASSWORD_DEFAULT),
        ];
        // dd($data);

        $userModel->update($id, $data);

        $response = [
            'status' => 'success',
            'message' => 'User updated successfully',
        ];
        return $this->response->setJSON($response);
    }

    public function destroy($id)
    {
        
        $User = new User();
        $User = $User->find($id);
        // dd($User);

        if (!$User) {
            $response = [
                'status' => 'error',
                'message' => 'User not found',
            ];
            return $this->response->setJSON($response)->setStatusCode(400);
        }

        // Hapus data pegawai berdasarkan ID
        $User->delete($id);

        $response = [
            'status' => 'success',
            'message' => 'User deleted',
        ];
        return $this->response->setJSON($response);
    }
}
