<?php namespace App\Database\Seeds;
  
class DataSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $this->call('EmployeesSeeder');
        $this->call('UsersSeeder');
    }
} 