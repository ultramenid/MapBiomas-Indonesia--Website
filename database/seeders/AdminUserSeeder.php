<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminPassword = env('SEED_ADMIN_PASSWORD');
        $editorPassword = env('SEED_EDITOR_PASSWORD');

        if (app()->isProduction() && (empty($adminPassword) || empty($editorPassword))) {
            throw new \RuntimeException('SEED_ADMIN_PASSWORD and SEED_EDITOR_PASSWORD environment variables are required in production.');
        }

        User::updateOrCreate(
            ['email' => 'admin@mapbiomas.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make($adminPassword ?: 'password'),
                'role_id' => User::ROLE_ADMIN,
            ]
        );

        User::updateOrCreate(
            ['email' => 'editor@mapbiomas.id'],
            [
                'name' => 'Editor',
                'password' => Hash::make($editorPassword ?: 'password'),
                'role_id' => User::ROLE_EDITOR,
            ]
        );
    }
}
