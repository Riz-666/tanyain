<?php

namespace Database\Factories;

use App\Models\ViewArtikel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ViewArtikelFactory extends Factory
{
    protected $model = ViewArtikel::class;

    public function definition(): array
    {
        return [
            'artikel_id' => null, // akan diisi di seeder
            'user_id' => null,    // akan diisi di seeder
            'user_agent' => $this->faker->userAgent(),
            'ip_address' => $this->faker->ipv4(),
        ];
    }
}
