<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateRoleTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'role_id' => [
                'type' => 'SMALLINT',
                'constraint' => 3,
                'unsigned'   => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'role_name' => [
                'type' => 'VARCHAR',
                'constraint' => 55,
                'null' => false,
            ],
            'role_access' => [
                'type' => 'VARCHAR',
                'constraint' => 55,
                'null' => true,
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

        $this->forge->addKey('role_id', true);

        $this->forge->addUniqueKey('role_name');

        $this->forge->createTable('role', true);
    }

    public function down()
    {
        $this->forge->dropTable('role', true);
    }
}
