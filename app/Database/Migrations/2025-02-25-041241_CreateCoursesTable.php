<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCoursesTable extends Migration
{

    //     id (Primary Key)
    // code (Unique)
    // name
    // credits
    // semester
    // created_at
    // updated_at

    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'          => 'INT',
                'constraint'    => 5,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'code' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'name' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'credits' => [
                'type'          => 'INT',
            ],
            'semester' => [
                'type'          => 'INT'
            ],
            'created_at' => [
                'type'          => 'timestamp'
            ],
            'updated_at' => [
                'type'          => 'timestamp'
            ],

        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('courses');
    }

    public function down()
    {
        //
        $this->forge->dropTable('courses');
    }
}
