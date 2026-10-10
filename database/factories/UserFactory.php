<?php

namespace Database\Factories;

use App\Enums\Teams;
use App\Enums\UserRoles;
use App\Enums\Zones;
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
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'team' => null,
            'zone' => null,
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

    /** Put the user in a team, with no zone (a team-wide role). */
    public function team(Teams $team): static
    {
        return $this->state(fn () => ['team' => $team]);
    }

    /** Put the user in a zone; the team is derived so the pair is always consistent. */
    public function zone(Zones $zone): static
    {
        return $this->state(fn () => [
            'zone' => $zone,
            'team' => $zone->team(),
        ]);
    }

    /**
     * Assign a role after creation. Roles must already exist - tests seed them
     * with RolesAndPermissionsSeeder.
     */
    public function role(UserRoles $role): static
    {
        return $this->afterCreating(fn ($user) => $user->assignRole($role->value));
    }
}
