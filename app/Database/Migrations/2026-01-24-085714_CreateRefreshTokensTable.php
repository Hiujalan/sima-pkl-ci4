<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRefreshTokensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'     => 'CHAR',
                'constraint' => 36,
            ],
            'token_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addUniqueKey('token_hash');

        $this->forge->addForeignKey(
            'user_id',
            'users',
            'user_id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('refresh_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('refresh_tokens');
    }
}
