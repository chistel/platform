<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           UserRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:27 PM
 */

namespace Platform\Repositories\Users;

use Platform\Eloquent\Repository;
use Platform\Events\Common\EntityCreated;
use Platform\Database\Eloquent\Models\Common\SocialProvider;
use Platform\Database\Eloquent\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

/**
 * Class UserRepository
 * @package Platform\Repositories\Users
 */
class UserRepository extends Repository
{
	/**
	 * @return string
	 */
	public function model()
	{
		return User::class;
	}


	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function addUser(Request $request): mixed
	{
		$request->merge(['enabled' => 1]);
		return DB::transaction(function () use ($request) {
			$createNew = false;
			$user = false;

			// there's a possibility a user with same email address is already in the system,
			// so we first check that, if it does, use the user object to add the social provider
			if (array_key_exists('provider', $request->toArray()) && array_key_exists('provider_id', $request->toArray())) {

				$user = $this->getModel()->where("email", $request->email)->first();
				if (!$user) {
					$createNew = true;
				}
			} else {
				$createNew = true;
			}

			$request->merge(['profile_id'=>randString(6)]);
			// if a fresh user, just create it
			if ($createNew) {

				$user = $this->create($request->except('password_confirmation'));
				$defaultRole = Role::where(['guard_name'=> 'user','name'=>'Tutee'])->first();
				$user->assignRole($defaultRole);
			}
			if (!is_null($user)) {
				// save social Account
				if (array_key_exists('provider', $request->toArray()) && array_key_exists('provider_id', $request->toArray())) {
//					$socialAccount = new SocialProvider();
//					$socialAccount->provider_id = $request->provider_id;
//					$socialAccount->provider = $request->provider;
//					$user->email_verified = now();
//					$user->socialProviders()->save($socialAccount);
				}

				if ($request->has('ref_data') && is_array($request->ref_data)) {
					if (isset($request->ref_data['referral_id']) && !is_null($request->ref_data['referral_id'])) {
						$user->referrer()->create($request->ref_data);
					}
				}
				// create activation token
				event(new EntityCreated($user, $createNew));
				return $user;
			}
			return false;
		});
	}


	/**
	 * @param $user
	 * @param $request
	 * @return mixed
	 */
	public function updateUser($user, $request): mixed
	{
		return DB::transaction(function () use ($user, $request) {
			$data = $request->toArray();
			$user->update($data);

			// a user is not expect to have more that one profile image.
			// so if there's a request for profile image upload,
			//first clear the old once
			if ($request->file('profile_image')) {
				$user->clearMediaCollection('profile_images');
				$user->addMediaFromRequest('profile_image')
					->preservingOriginal()->toMediaCollection('profile_images');
			}
			if(!is_null($request->meta) && is_array($request->meta)) {
				foreach ($request->meta as $metaKey => $metaValue) {
					$user->setMeta($metaKey, $metaValue);
				}
			}
			return $user;
		});
	}

	/**
	 * Returns first and last name from name
	 *
	 * @param string $name
	 * @return array
	 */
	public function getSocialProviderFirstLastName(string $name)
	{
		$name = trim($name);

		$lastName = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);

		$firstName = trim(preg_replace('#' . $lastName . '#', '', $name));

		return [
			'first_name' => $firstName,
			'last_name' => $lastName,
		];
	}
}
