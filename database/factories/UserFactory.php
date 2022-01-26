<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  hailatutor
 * @file                           UserFactory.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     07/02/2021, 3:47 AM
 */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Platform\Database\Eloquent\Models\Users\User;
use Spatie\Permission\Models\Role;

class UserFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = User::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'uuid' => $this->faker->uuid,
			'username' => $this->faker->userName,
			'phone' => $this->faker->phoneNumber,
			'intl_phone' => $this->faker->e164PhoneNumber,
			'phone_verified_at' => now(),
			'first_name' => $this->faker->firstName,
			'last_name' => $this->faker->lastName,
			'email' => $this->faker->unique()->safeEmail,
			'email_verified_at' => now(),
			'password' => 'password', // password
			'remember_token' => Str::random(10),
		];
	}

	/**
	 * Configure the model factory.
	 *
	 * @return $this
	 */
	public function configure()
	{
		return $this->afterCreating(function (User $user) {
			$randomRole = Role::where('guard_name', 'user')->inRandomOrder()->first();
			$user->assignRole($randomRole);

		});
	}

}
