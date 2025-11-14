<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\UserRole;
use App\Services\RoleService;
use Illuminate\Database\Seeder; // Base seeder class provided by Laravel
use Illuminate\Support\Facades\DB; // Facade for interacting with the database
use Illuminate\Support\Str; // Helper class for generating strings (e.g., UUIDs)

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This method will be called when running the database seeder. It inserts
     * predefined roles into the 'roles' table.
     *
     * @return void
     */
    public function run()
    {
        // Check if the admin role exists, if so, delete it
        $admin_role = Role::where("role_name", RoleService::ADMIN_ROLE)->first();
        if ($admin_role) {
            $admin_role->delete();
        }

        // Check if the user role exists, if so, delete it
        $user_role = Role::where("role_name", RoleService::USER_ROLE)->first();
        if ($user_role) {
            $user_role->delete();
        }

         // Check if the user role exists, if so, delete it
         $user_role = Role::where("role_name", RoleService::SUB_ADMIN_ROLE)->first();
         if ($user_role) {
             $user_role->delete();
         }

        // Insert the "ROLE_ADMIN" into the 'roles' table with a generated UUID
        DB::table('roles')->insert([
            'role_id' => Str::uuid(), // Generate a unique UUID for the role
            'role_name' => RoleService::ADMIN_ROLE, // Set the role name as "ROLE_ADMIN"
        ]);

        // Insert the "ROLE_USER" into the 'roles' table with a generated UUID
        DB::table('roles')->insert([
            'role_id' => Str::uuid(), // Generate a unique UUID for the role
            'role_name' => RoleService::USER_ROLE, // Set the role name as "ROLE_USER"
        ]);

         // Insert the "ROLE_USER" into the 'roles' table with a generated UUID
         DB::table('roles')->insert([
            'role_id' => Str::uuid(), // Generate a unique UUID for the role
            'role_name' => RoleService::SUB_ADMIN_ROLE, // Set the role name as "SUB_ADMIN_ROLE"
        ]);
    }
}
