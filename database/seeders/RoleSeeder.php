<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'rider' => 'Calaret',
            'guardian' => 'Parinte / Tutore',
            'monitor' => 'Monitor',
            'center' => 'Centru de echitatie',
            'federation' => 'Federatie',
        ];

        Role::query()->whereNotIn('code', array_keys($roles))->delete();

        foreach ($roles as $code => $name) {
            Role::query()->updateOrCreate(['code' => $code], ['name' => $name]);
        }
    }
}
