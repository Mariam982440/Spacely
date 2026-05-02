<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\ArchitectProfile;
use App\Models\ClientProfile;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole    = Role::where('slug', 'admin')->first();
        $architectRole = Role::where('slug', 'architect')->first();
        $clientRole   = Role::where('slug', 'client')->first();

        // ── Admin ──────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@spacely.com'],
            [
                'name'     => 'Admin Spacely',
                'password' => Hash::make('password'),
                'role_id'  => $adminRole->id,
            ]
        );

        // ── Architectes ────────────────────────────────────
        $architects = [
            [
                'user' => [
                    'name'     => 'Karim Benali',
                    'email'    => 'karim@spacely.com',
                    'password' => Hash::make('password'),
                    'role_id'  => $architectRole->id,
                ],
                'profile' => [
                    'bio'              => 'Spécialisé dans le design contemporain et minimaliste, je transforme vos espaces avec des lignes épurées et des matériaux nobles. 10 ans d\'expérience à Casablanca.',
                    'city'             => 'Casablanca',
                    'experience_years' => 10,
                    'is_verified'      => true,
                ],
            ],
            [
                'user' => [
                    'name'     => 'Sara El Fassi',
                    'email'    => 'sara@spacely.com',
                    'password' => Hash::make('password'),
                    'role_id'  => $architectRole->id,
                ],
                'profile' => [
                    'bio'              => 'Architecte d\'intérieur passionnée par le style bohème et scandinave. Chaque projet est une histoire unique que nous écrivons ensemble.',
                    'city'             => 'Marrakech',
                    'experience_years' => 6,
                    'is_verified'      => true,
                ],
            ],
            [
                'user' => [
                    'name'     => 'Youssef Idrissi',
                    'email'    => 'youssef@spacely.com',
                    'password' => Hash::make('password'),
                    'role_id'  => $architectRole->id,
                ],
                'profile' => [
                    'bio'              => 'Architecte industriel basé à Rabat. Je mets en valeur les espaces bruts : béton, acier et bois combinés pour un résultat saisissant.',
                    'city'             => 'Rabat',
                    'experience_years' => 4,
                    'is_verified'      => false, // En attente validation admin
                ],
            ],
        ];

        foreach ($architects as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['user']['email']],
                $data['user']
            );

            ArchitectProfile::firstOrCreate(
                ['user_id' => $user->id],
                array_merge($data['profile'], ['user_id' => $user->id])
            );
        }

        // ── Clients ────────────────────────────────────────
        $clients = [
            [
                'name'  => 'Nadia Chraibi',
                'email' => 'nadia@spacely.com',
                'phone' => '+212 6 11 22 33 44',
            ],
            [
                'name'  => 'Omar Tazi',
                'email' => 'omar@spacely.com',
                'phone' => '+212 6 55 66 77 88',
            ],
            [
                'name'  => 'Leila Mansouri',
                'email' => 'leila@spacely.com',
                'phone' => null,
            ],
        ];

        foreach ($clients as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => Hash::make('password'),
                    'role_id'  => $clientRole->id,
                ]
            );

            ClientProfile::firstOrCreate(
                ['user_id' => $user->id],
                ['user_id' => $user->id, 'phone' => $data['phone']]
            );
        }
    }
}