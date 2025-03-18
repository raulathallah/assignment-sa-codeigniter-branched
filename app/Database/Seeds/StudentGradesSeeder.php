<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class StudentGradesSeeder extends Seeder
{
    public function run()
    {
        //
        $data = [
            [
                'enrollment_id'         => 1,
                'grade_value'           => 88,
                'grade_letter'          => "B",
                'completed_at'          => new Time(),
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
            [
                'enrollment_id'         => 2,
                'grade_value'           => 92,
                'grade_letter'          => "A",
                'completed_at'          => new Time(),
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
            [
                'enrollment_id'         => 3,
                'grade_value'           => 84,
                'grade_letter'          => "B",
                'completed_at'          => new Time(),
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
        ];
        $this->db->table('student_grades')->insertBatch($data);
    }
}
