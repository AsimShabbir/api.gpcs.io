<?php

namespace Database\Seeders;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $roles = [
            [
                'name' => 'Admin',
                'is_deleted' => false,
            ],

            [
                'name' => 'User',
                'is_deleted' => false,
            ],
            // Add more roles as needed
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
