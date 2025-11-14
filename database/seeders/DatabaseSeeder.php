<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder; // Base Seeder class provided by Laravel

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * This method is responsible for seeding the entire database. It uses
     * other seeders to populate tables in the database with predefined data.
     *
     * @return void
     */
    public function run()
    {
        // Call multiple seeders to seed the database with initial data.
        // These seeders are responsible for populating specific tables.
        $this->call([
            RoleSeeder::class, // Calls the RoleSeeder to populate the 'roles' table
            PermissionSeeder::class, // Calls the PermissionSeeder to populate the 'permissions' table
        ]);
    }
}