<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class EnrollmentsSeeder extends Seeder
{
    public function run()
    {
        //
        $data = [
            [
                'student_id'            => 1,
                'course_id'             => 1,
                'academic_year'         => 2025,
                'semester'              => 1,
                'status'                => 'enrolled',
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
            [
                'student_id'            => 1,
                'course_id'             => 2,
                'academic_year'         => 2025,
                'semester'              => 1,
                'status'                => 'enrolled',
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
            [
                'student_id'            => 2,
                'course_id'             => 1,
                'academic_year'         => 2025,
                'semester'              => 1,
                'status'                => 'enrolled',
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
        ];
        $this->db->table('enrollments')->insertBatch($data);
    }
}
