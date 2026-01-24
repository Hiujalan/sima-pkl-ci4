<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateStudentTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'student_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],
            'user_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],
            'student_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'student_nis' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],
            'class_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],
            'student_year' => [
                'type' => 'SMALLINT',
                'constraint' => 4,
                'null' => false,
                'comment'    => 'Tahun masuk / angkatan',
            ],
            'is_active' => [
                'type' => 'SMALLINT',
                'constraint' => 1,
                'default'    => 1,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('student_id', true);

        $this->forge->addUniqueKey('student_nis');

        $this->forge->addKey('user_id');
        $this->forge->addKey('class_id');

        $this->forge->createTable('student', true);

        $this->forge->addForeignKey(
            'class_id',
            'class',
            'class_id',
            'CASCADE',
            'RESTRICT'
        );
    }

    public function down()
    {
        $this->forge->dropTable('student', true);
    }
}
