<?php

namespace Database\Factories;

use App\Models\Artikel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArtikelFactory extends Factory
{
    protected $model = Artikel::class;

    public function definition(): array
{
    return [
        'user_id' => 1,
        'repositori_id' => 1,
        'judul' => $this->faker->sentence(6),
        'slug' => $this->faker->unique()->slug(),
        'isi' => $this->faker->paragraphs(5, true),
        'file' => null,
        'status' => 'aktif',
        'cover' => null,
        'views' => 0,
        'deleted_until' => null,
    ];
}
}
