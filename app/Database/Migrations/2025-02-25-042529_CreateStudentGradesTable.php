<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentGradesTable extends Migration
{
    public function up()
    {
        //
        // id (Primary Key)
        // enrollment_id (Foreign Key)
        // grade_value
        // grade_letter
        // completed_at
        // created_at
        // updated_at

        $this->forge->addField([
            'id' => [
                'type'          => 'INT',
                'constraint'    => 5,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'enrollment_id' => [
                'type'          => 'INT',
            ],
            'grade_value' => [
                'type'          => 'FLOAT',
            ],
            'grade_letter' => [
                'type'          => 'CHAR',
                'contraint'     => 2
            ],
            'completed_at' => [
                'type'          => 'DATE'
            ],
            'created_at' => [
                'type'          => 'timestamp'
            ],
            'updated_at' => [
                'type'          => 'timestamp'
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('enrollment_id', 'enrollments', 'id');
        $this->forge->createTable('student_grades');
    }

    public function down()
    {
        //
        $this->forge->dropTable('student_grades');
    }
}
