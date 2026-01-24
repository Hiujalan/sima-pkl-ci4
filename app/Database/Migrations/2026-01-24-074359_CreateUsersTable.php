<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],
            'user_name' => [
                'type' => 'VARCHAR',
                'constraint' => 60,
                'null' => false,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'user_role' => [
                'type' => 'SMALLINT',
                'constraint' => 3,
                'unsigned'   => true,
                'default'    => 1,
                'null' => false,
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

        $this->forge->addKey('user_id', true);

        $this->forge->addUniqueKey('user_name');

        $this->forge->addKey('user_role');

        $this->forge->addForeignKey(
            'user_role',
            'role',
            'role_id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->forge->createTable('users', true);
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}
