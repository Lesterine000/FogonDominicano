<?php

namespace Database\Seeders;

use App\Models\Dish;
use Illuminate\Database\Seeder;

class DishSeeder extends Seeder
{
    public function run(): void
    {
        Dish::updateOrCreate(
            ['service_date' => now()->toDateString()],
            [
                'name' => 'Sancocho Dominicano',
                'description' => 'El clasico de siete carnes, servido con arroz y aguacate.',
                'price' => 15.00,
                'service_date' => now()->toDateString(),
                'available_servings' => 50,
                'is_active' => true,
            ]
        );
    }
}
