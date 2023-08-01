<?php

namespace App\Controllers;

use App\Models\Employee;

class EmployeeController extends BaseController
{
    public function index()
    {
        $Employee = new Employee();
        $employees = $Employee->findAll();

        // Mengirimkan respons JSON
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $employees,
        ]);
    }

    public function create()
    {
        $request = service('request');

        // Validasi input data pegawai
        $validationRules = [
            'name' => 'required',
            'email' => 'required|valid_email',
            'photo' => 'uploaded[photo]|max_size[photo,300]',
        ];

        if (!$this->validate($validationRules)) {
            $response = [
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors(),
            ];

            return $this->response->setJSON($response)->setStatusCode(400);
        }

        // Jika validasi berhasil, simpan data pegawai ke database
        $Employee = new Employee();

        $data = [
            'name' => $request->getPost('name'),
            'email' => $request->getPost('email'),
        ];

        // Jika ada file foto yang diunggah, simpan file dan tambahkan nama file ke data pegawai
        $photo = $request->getFile('photo');
        if ($photo->isValid() && !$photo->hasMoved()) {
            $newName = $photo->getRandomName();
            $photo->move(ROOTPATH . 'public/uploads', $newName);
            $data['photo'] = $newName;
        }

        // Insert data pegawai ke database
        $Employee->insert($data);

        // Berikan respons sukses
        $response = [
            'status' => 'success',
            'message' => 'Employee created successfully',
        ];

        return $this->response->setJSON($response);
    }

    public function edit($id)
    {
        $request = service('request');

        // dd($id);

        // Validasi input data pegawai
        $validationRules = [
            'name' => 'required',
            'email' => 'required|valid_email',
            // 'photo' => 'uploaded[photo]|max_size[photo,300]',
        ];

        if (!$this->validate($validationRules)) {
            $response = [
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors(),
            ];

            return $this->response->setJSON($response)->setStatusCode(400);
        }

        // Jika validasi berhasil, perbarui data pegawai di database
        $Employee = new Employee();
        $employee = $Employee->find($id);
        // dd($employee);

        if (!$employee) {
            $response = [
                'status' => 'error',
                'message' => 'Employee not found',
            ];

            return $this->response->setJSON($response)->setStatusCode(404);
        }

        $data = [
            'name' => $request->getVar('name'),
            'email' => $request->getVar('email'),
        ];

        // Jika ada file foto yang diunggah, simpan file dan tambahkan nama file ke data pegawai
        $photo = $this->request->getFile('photo');

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            // Simpan foto ke dalam folder uploads
            $newName = $photo->getRandomName();
            $photo->move(ROOTPATH . 'public/uploads', $newName);
            $data['photo'] = $newName;

            // Hapus file foto lama jika ada
            if ($employee['photo'] && file_exists(ROOTPATH . 'public/uploads/' . $employee['photo'])) {
                unlink(ROOTPATH . 'public/uploads/' . $employee['photo']);
            }
        } else {
            // Jika tidak ada foto yang diupload, gunakan foto yang ada sebelumnya
            $newName = $employee['photo'];
        }

        // Update data pegawai di database
        $Employee->update($id, $data);

        // Berikan respons sukses
        $response = [
            'status' => 'success',
            'message' => 'Employee updated successfully',
        ];

        return $this->response->setJSON($response);
    }

    public function show($id)
    {
        $Employee = new Employee();
        $employee = $Employee->find($id);

        if (!$employee) {
            $response = [
                'status' => 'error',
                'message' => 'Employee not found',
            ];
            return $this->response->setJSON($response)->setStatusCode(400);
        }

        $response = [
            'status' => 'success',
            'message' => 'Employee found',
            'data' => $employee,
        ];

        return $this->response->setJSON($response);
    }

    public function destroy($id)
    {
        $Employee = new Employee();
        $employee = $Employee->find($id);

        if (!$employee) {
            $response = [
                'status' => 'error',
                'message' => 'Employee not found',
            ];
            return $this->response->setJSON($response)->setStatusCode(400);
        }

        // Hapus data pegawai berdasarkan ID
        $Employee->delete($id);

        $response = [
            'status' => 'success',
            'message' => 'Employee deleted',
        ];
        return $this->response->setJSON($response);
    }
}
