<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Ramsey\Uuid\Uuid;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'user_id' => Uuid::uuid4()->toString(),
                'user_name' => 'admin@example.com',
                'password' => password_hash('sayalupa@admin', PASSWORD_BCRYPT),
                'user_role' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($users);
    }
}
