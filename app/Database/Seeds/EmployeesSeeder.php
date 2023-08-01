<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EmployeesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Alice Johnson',
                'email' => 'alice@example.com',
                'photo' => 'alice.jpg',
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'bob@example.com',
                'photo' => 'bob.jpg',
            ],
        ];

        $this->db->table('employees')->insertBatch($data);
    }
}
