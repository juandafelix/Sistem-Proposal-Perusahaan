<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        $users = [
            [
                'name'     => 'Budi Santoso',
                'email'    => 'manager@perusahaan.com',
                'role'     => 'manager',
                'division' => null,
            ],
            [
                'name'     => 'Siti Rahayu',
                'email'    => 'finance@perusahaan.com',
                'role'     => 'finance',
                'division' => 'Keuangan',
            ],
            [
                'name'     => 'Ahmad Fauzi',
                'email'    => 'divisi1@perusahaan.com',
                'role'     => 'division_1',
                'division' => 'Teknologi Informasi',
            ],
            [
                'name'     => 'Dewi Kusuma',
                'email'    => 'divisi2@perusahaan.com',
                'role'     => 'division_2',
                'division' => 'Sumber Daya Manusia',
            ],
            [
                'name'     => 'Rizky Pratama',
                'email'    => 'divisi3@perusahaan.com',
                'role'     => 'division_3',
                'division' => 'Pemasaran',
            ],
            [
                'name'     => 'Intan Permata',
                'email'    => 'divisi4@perusahaan.com',
                'role'     => 'division_4',
                'division' => 'Operasional',
            ],
            [
                'name'     => 'Hendra Gunawan',
                'email'    => 'divisi5@perusahaan.com',
                'role'     => 'division_5',
                'division' => 'Penelitian & Pengembangan',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name'=> $userData['name'],
                    'role'=> $userData['role'],
                    'division'=> $userData['division'],
                    'password'=> Hash::make('password123'),
                ]
            );
        }

        $this->command->info('✅ User seeder selesai! Semua user berhasil dibuat.');
        $this->command->table(
            ['Name', 'Email', 'Role', 'Division'],
            collect($users)->map(fn($u) => [
                $u['name'],
                $u['email'],
                $u['role'],
                $u['division'] ?? '-',
            ])->toArray()
        );
    }
}
