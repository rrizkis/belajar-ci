<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        for ($i = 0; $i < 10; $i++) {
            $data = [
                'username' => $faker->userName,
                'email' => $faker->email,
                'password' => password_hash('1234567', PASSWORD_DEFAULT),
                'role' => 'user', // Add a role
                'created_at' => date("Y-m-d H:i:s"),
            ];
            print_r($data);
            $this->db->table('user')->insert($data);
        }

        // Insert a specific admin user
        $adminData = [
            'username' => 'rizqysaputra',
            'email' => 'admin@example.com',
            'password' => password_hash('1234567', PASSWORD_DEFAULT),
            'role' => 'admin',
            'created_at' => date("Y-m-d H:i:s"),
        ];
        $this->db->table('user')->insert($adminData);
    }
}
