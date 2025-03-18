<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnrollmentsTable extends Migration
{
    // id (Primary Key)
    // student_id (Foreign Key)
    // course_id (Foreign Key)
    // academic_year
    // semester
    // status
    // created_at
    // updated_at

    public function up()
    {
        //
        $this->forge->addField([
            'id' => [
                'type'          => 'INT',
                'constraint'    => 5,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'student_id' => [
                'type'          => 'INT',
            ],
            'course_id' => [
                'type'          => 'INT',
            ],
            'academic_year' => [
                'type'          => 'INT',
            ],
            'semester' => [
                'type'          => 'INT',
            ],
            'status' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'created_at' => [
                'type'          => 'timestamp'
            ],
            'updated_at' => [
                'type'          => 'timestamp'
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('student_id', 'students', 'id');
        $this->forge->addForeignKey('course_id', 'courses', 'id');
        $this->forge->createTable('enrollments');
    }

    public function down()
    {
        //
        $this->forge->dropTable('enrollments');
    }
}
