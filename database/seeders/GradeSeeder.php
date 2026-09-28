<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $grades = [
            ['code' => 'rider_bronze', 'name' => 'Calaret de Bronz', 'rank' => 1, 'description' => 'Ciclul 1, etapa 1'],
            ['code' => 'rider_silver', 'name' => 'Calaret de Argint', 'rank' => 2, 'description' => 'Ciclul 1, etapa 2'],
            ['code' => 'rider_gold', 'name' => 'Calaret de Aur', 'rank' => 3, 'description' => 'Ciclul 1, etapa 3'],
            ['code' => 'cavalier_bronze', 'name' => 'Cavaler de Bronz', 'rank' => 4, 'description' => 'Ciclul 2, etapa 1'],
            ['code' => 'cavalier_silver', 'name' => 'Cavaler de Argint', 'rank' => 5, 'description' => 'Ciclul 2, etapa 2'],
            ['code' => 'cavalier_gold', 'name' => 'Cavaler de Aur', 'rank' => 6, 'description' => 'Ciclul 2, etapa 3'],
        ];

        foreach ($grades as $grade) {
            Grade::query()->updateOrCreate(['code' => $grade['code']], $grade);
        }
    }
}
