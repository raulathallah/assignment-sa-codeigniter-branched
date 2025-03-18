<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AuthGroupSeeder extends Seeder
{
    public function run()
    {
        //
        $data = [
            [
                'name'            => 'admin',
                'description'     => 'role admin',
            ],
            [
                'name'            => 'student',
                'description'     => 'role student',
            ],
            [
                'name'            => 'lecturer',
                'description'     => 'role lecturer',
            ],
        ];
        $this->db->table('auth_groups')->insertBatch($data);
    }
}
