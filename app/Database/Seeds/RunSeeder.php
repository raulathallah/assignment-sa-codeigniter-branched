<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RunSeeder extends Seeder
{
    public function run()
    {
        $this->call('StudentsSeeder');
        $this->call('CoursesSeeder');
        $this->call('EnrollmentsSeeder');
        $this->call('StudentGradesSeeder');
        $this->call('AuthGroupSeeder');
    }
}
