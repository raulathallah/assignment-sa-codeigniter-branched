<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class CoursesSeeder extends Seeder
{
    public function run()
    {
        //
        $data = [
            [
                'code'                  => 'CRS00001',
                'name'                  => 'Algebra',
                'credits'               => 4,
                'semester'              => 1,
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
            [
                'code'      => 'CRS00002',
                'name'      => 'Database System',
                'credits'   => 4,
                'semester'  => 2,
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
            [
                'code'      => 'CRS00003',
                'name'      => 'Python Programming',
                'credits'   => 2,
                'semester'  => 3,
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
            [
                'code'      => 'CRS00004',
                'name'      => 'Java Programming',
                'credits'   => 2,
                'semester'  => 4,
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
            [
                'code'      => 'CRS00005',
                'name'      => 'Bahasa Indonesia',
                'credits'   => 2,
                'semester'  => 4,
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
            [
                'code'      => 'CRS00006',
                'name'      => 'Bahasa Indonesia',
                'credits'   => 1,
                'semester'  => 5,
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
            [
                'code'      => 'CRS00007',
                'name'      => 'Bahasa Ingriss',
                'credits'   => 2,
                'semester'  => 3,
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
            [
                'code'      => 'CRS00008',
                'name'      => 'Software Engineering',
                'credits'   => 6,
                'semester'  => 2,
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
            [
                'code'      => 'CRS00009',
                'name'      => 'User Interface',
                'credits'   => 3,
                'semester'  => 5,
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
        ];
        $this->db->table('courses')->insertBatch($data);
    }
}
