<?php

namespace Database\Factories;

use App\Models\Repositori;
use Illuminate\Database\Eloquent\Factories\Factory;

class RepositoriFactory extends Factory
{
    protected $model = Repositori::class;

    public function definition(): array
{
    return [
        'user_id' => 1, // akan di-override di seeder
        'judul_repo' => $this->faker->sentence(4),
        'deskripsi' => $this->faker->paragraph(),
        'status' => 'aktif',
        'deleted_until' => null,
    ];
}
}
