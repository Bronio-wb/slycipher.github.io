<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = fake('es_ES')->firstName();
        $lastName = fake('es_ES')->lastName();
        
        return [
            'name' => $firstName . ' ' . $lastName,
            'nombre' => $firstName,
            'apellido' => $lastName,
            'username' => strtolower($firstName . '.' . $lastName . rand(100, 999)),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'telefono' => fake('es_ES')->phoneNumber(),
            'fecha_nacimiento' => fake()->dateTimeBetween('-50 years', '-18 years'),
            'rol' => 'estudiante',
            'estado' => 'activo',
            'puntos_totales' => rand(0, 500),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create an admin user.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'admin',
            'puntos_totales' => rand(1000, 2000),
        ]);
    }

    /**
     * Create a developer user.
     */
    public function developer(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'desarrollador',
            'puntos_totales' => rand(500, 1000),
        ]);
    }

    /**
     * Create a student user.
     */
    public function student(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'estudiante',
            'puntos_totales' => rand(0, 500),
        ]);
    }
}
