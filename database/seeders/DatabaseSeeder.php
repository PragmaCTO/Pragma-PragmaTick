<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with the default super admin user.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@pragmacto.com'],
            [
                'name' => 'Super Admin',
                'emails' => ['superadmin@pragmacto.com'],
                'phone_number' => '+1-555-0000',
                'password' => Hash::make('changemeplease'),
                'is_super_admin' => true,
            ]
        );
    }
}
