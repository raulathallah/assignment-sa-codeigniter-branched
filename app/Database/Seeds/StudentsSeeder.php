<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class StudentsSeeder extends Seeder
{
    public function run()
    {
        //
        $data = [
            [
                'student_id'            => '2301893013',
                'name'                  => 'Jodi B',
                'study_program'         => 'Computer Science',
                'current_semester'      => 1,
                'academic_status'       => 'active',
                'entry_year'            => '2025',
                'gpa'                   => 3.56,
                'created_at'            => new Time(),
                'updated_at'            => null,
                'deleted_at'            => null,
            ],
            [
                'student_id'            => '2301893244',
                'name'                  => 'Tatang B',
                'study_program'         => 'Finance',
                'current_semester'      => 1,
                'academic_status'       => 'on leave',
                'entry_year'            => '2025',
                'gpa'                   => 3.22,
                'created_at'            => new Time(),
                'updated_at'            => null,
                'deleted_at'            => null,
            ],
            [
                'student_id'            => '2301894421',
                'name'                  => 'Bocit B',
                'study_program'         => 'Computer Science',
                'current_semester'      => 4,
                'academic_status'       => 'on leave',
                'entry_year'            => '2023',
                'gpa'                   => 3.77,
                'created_at'            => new Time(),
                'updated_at'            => null,
                'deleted_at'            => null,
            ],
            [
                'student_id'            => '23018941113',
                'name'                  => 'Kale B',
                'study_program'         => 'Computer Science',
                'current_semester'      => 8,
                'academic_status'       => 'graduated',
                'entry_year'            => '2021',
                'gpa'                   => 3.42,
                'created_at'            => new Time(),
                'updated_at'            => null,
                'deleted_at'            => null,
            ],
        ];
        $this->db->table('students')->insertBatch($data);
    }
}
