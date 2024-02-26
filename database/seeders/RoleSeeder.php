<?php

namespace Database\Seeders;

use App\Enums\RolesName;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => RolesName::ADMIN]);
        Role::create(['name' => RolesName::FINANCIAL]);
        Role::create(['name' => RolesName::EMPLOYEE]);
    }
}
