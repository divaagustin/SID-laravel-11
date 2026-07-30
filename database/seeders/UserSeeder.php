<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Super Admin',
                'email'    => 'superadmin@desa.go.id',
                'role'     => UserRole::Administrator,
                'password' => 'password',
            ],
            [
                'name'     => 'Admin Desa',
                'email'    => 'admin@desa.id',
                'role'     => UserRole::Administrator,
                'password' => 'password',
            ],
            [
                'name'     => 'Operator Surat & Data',
                'email'    => 'operator@desa.id',
                'role'     => UserRole::Operator,
                'password' => 'password',
            ],
            [
                'name'     => 'Sekretaris Desa (Sekdes)',
                'email'    => 'sekdes@desa.id',
                'role'     => UserRole::SekretarisDesa,
                'password' => 'password',
            ],
            [
                'name'     => 'Kepala Desa (Kades)',
                'email'    => 'kades@desa.id',
                'role'     => UserRole::KepalaDesa,
                'password' => 'password',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name'      => $userData['name'],
                    'role'      => $userData['role'],
                    'password'  => $userData['password'],
                    'is_active' => true,
                ]
            );
        }
    }
}
