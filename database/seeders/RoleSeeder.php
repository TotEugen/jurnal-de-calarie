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
            'rider' => 'Călăreț',
            'monitor' => 'Monitor',
            'center' => 'Centru de echitație',
            'federation' => 'Federație',
        ];

        Role::query()->whereNotIn('code', array_keys($roles))->delete();

        foreach ($roles as $code => $name) {
            Role::query()->updateOrCreate(['code' => $code], ['name' => $name]);
        }
    }
}
