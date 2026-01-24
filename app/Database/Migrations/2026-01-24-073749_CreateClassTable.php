<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateClassTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'class_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],
            'class_name' => [
                'type' => 'VARCHAR',
                'constraint' => 55,
                'null' => false,
            ],
            'class_department' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],
            'is_active' => [
                'type' => 'SMALLINT',
                'constraint' => 1,
                'default' => 1,
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

        $this->forge->addKey('class_id', true);
        $this->forge->addKey('class_department');
        $this->forge->addForeignKey(
            'class_department',
            'department',
            'department_id',
            'CASCADE',
            'RESTRICT'
        );
        $this->forge->createTable('class', true);
    }

    public function down()
    {
        $this->forge->dropTable('class', true);
    }
}
