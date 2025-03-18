<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'          => 'INT',
                'constraint'    => 5,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'student_id' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'user_id' => [
                'type'          => 'INT',
                'default'       => null
            ],
            'name' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'study_program' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'current_semester' => [
                'type'          => 'INT',
            ],
            'academic_status' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'entry_year' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'gpa' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'created_at'        => [
                'type'           => 'DATETIME',
                'null'           => false,
                //'default'        => 'CURRENT_TIMESTAMP',
            ],
            'updated_at'        => [
                'type'           => 'DATETIME',
                'null'           => false,
                //'default'        => 'CURRENT_TIMESTAMP',
            ],
            'deleted_at'        => [
                'type'           => 'DATETIME',
                'null'           => true,
                'default'        => null,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('students');

        $db = \Config\Database::connect();
        $db->query('ALTER TABLE "students" ALTER COLUMN "created_at" SET DEFAULT CURRENT_TIMESTAMP');
        $db->query('ALTER TABLE "students" ALTER COLUMN "updated_at" SET DEFAULT CURRENT_TIMESTAMP');
    }

    public function down()
    {
        //
        $this->forge->dropTable('students');
    }
}
