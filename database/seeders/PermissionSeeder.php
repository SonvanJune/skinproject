<?php

namespace Database\Seeders;

use App\Models\Permission; // Importing the Permission model
use Illuminate\Database\Seeder; // Base Seeder class provided by Laravel
use Illuminate\Support\Facades\DB; // Facade for database operations
use Illuminate\Support\Str; // Helper class for generating strings (e.g., UUIDs)

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This method will be called when running the database seeder. It inserts
     * predefined permissions into the 'permissions' table.
     *
     * @return void
     */
    public function run()
    {
        // Delete all existing records in the 'permissions' table to avoid duplicate data
        Permission::query()->delete();

        // Inserting predefined permissions into the 'permissions' table

        DB::table('permissions')->insert([
            'permission_id' => Str::uuid(), // Generate a unique UUID for the permission
            'permission_name' => "view users" // Permission to view users
        ]);

        DB::table('permissions')->insert([
            'permission_id' => Str::uuid(), // Generate a unique UUID for the permission
            'permission_name' => "edit users" // Permission to edit users
        ]);

        DB::table('permissions')->insert([
            'permission_id' => Str::uuid(), // Generate a unique UUID for the permission
            'permission_name' => "create users" // Permission to create new users
        ]);

        DB::table('permissions')->insert([
            'permission_id' => Str::uuid(), // Generate a unique UUID for the permission
            'permission_name' => "delete posts" // Permission to delete posts
        ]);

        DB::table('permissions')->insert([
            'permission_id' => Str::uuid(), // Generate a unique UUID for the permission
            'permission_name' => "view posts" // Permission to view posts
        ]);

        DB::table('permissions')->insert([
            'permission_id' => Str::uuid(), // Generate a unique UUID for the permission
            'permission_name' => "edit posts" // Permission to edit posts
        ]);

        DB::table('permissions')->insert([
            'permission_id' => Str::uuid(), // Generate a unique UUID for the permission
            'permission_name' => "create posts" // Permission to create new posts
        ]);

        DB::table('permissions')->insert([
            'permission_id' => Str::uuid(), // Generate a unique UUID for the permission
            'permission_name' => "view roles" // Permission to view roles
        ]);

        DB::table('permissions')->insert([
            'permission_id' => Str::uuid(), // Generate a unique UUID for the permission
            'permission_name' => "edit roles" // Permission to edit roles
        ]);

        DB::table('permissions')->insert([
            'permission_id' => Str::uuid(), // Generate a unique UUID for the permission
            'permission_name' => "create roles" // Permission to create new roles
        ]);
    }
}
