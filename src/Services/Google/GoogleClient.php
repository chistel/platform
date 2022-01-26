<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           GoogleClient.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     04/12/2021, 9:01 AM
 */

namespace Platform\Services\Google;

use Exception;
use Google_Client;
use Google_Service_Calendar;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Platform\Exceptions\GoogleClientInvalidConfiguration;
class GoogleClient
{
	/**
	 * @var Google_Client
	 */
	protected Google_Client $client;

	/**
	 * @var Repository|Application|mixed
	 */
	protected mixed $config;

	/**
	 * Google constructor.
	 *
	 * @throws GoogleClientInvalidConfiguration
	 * @throws \Google\Exception
	 */
	public function __construct($accessType = 'offline')
	{
		$config = config('services.google');
		$client = new Google_Client();
		/*$client->setClientId(config('services.google.client_id'));
		$client->setClientSecret(config('services.google.client_secret'));
		if ($accessType !== 'offline') {
			$client->setRedirectUri(config('services.google.redirect_uri'));
			$client->setApprovalPrompt(config('services.google.approval_prompt'));
		}
		$client->setScopes(config('services.google.scopes'));
		$client->setAccessType($accessType ?? config('services.google.access_type'));
		$client->setIncludeGrantedScopes(config('services.google.include_granted_scopes'));*/
		$this->client = $client;

		$this->guardAgainstInvalidConfiguration($config);

	}

	/**
	 * @throws \Google\Exception
	 * @throws GoogleClientInvalidConfiguration
	 */
	public function createAuthenticatedGoogleClient(array $config): Google_Client
	{
		$authProfile = $config['default_auth_profile'];

		if ($authProfile === 'service_account') {
			return self::createServiceAccountClient($config['auth_profiles']['service_account']);
		}
		if ($authProfile === 'oauth') {
			return self::createOAuthClient($config['auth_profiles']['oauth']);
		}

		throw GoogleClientInvalidConfiguration::invalidAuthenticationProfile($authProfile);
	}

	/**
	 * @throws \Google\Exception
	 */
	protected function createServiceAccountClient(array $authProfile): Google_Client
	{
		$this->client->setScopes([
			Google_Service_Calendar::CALENDAR,
		]);

		$this->client->setAuthConfig($authProfile['credentials_json']);

		return $this->client;
	}

	/**
	 * @param array $authProfile
	 * @return Google_Client
	 * @throws \Google\Exception
	 */
	protected function createOAuthClient(array $authProfile): Google_Client
	{
		$this->client->setScopes([
			Google_Service_Calendar::CALENDAR,
		]);

		$this->client->setAuthConfig($authProfile['credentials_json']);

		$this->client->setAccessToken(file_get_contents($authProfile['token_json']));

		return $this->client;
	}

	/**
	 * @param $token
	 * @return $this
	 */
	public function connectUsing($token): static
	{
		$this->client->setAccessToken($token);

		return $this;
	}

	/**
	 * @param null $token
	 * @return bool
	 */
	public function revokeToken($token = null): bool
	{
		$token = $token ?? $this->client->getAccessToken();

		return $this->client->revokeToken($token);
	}

	/**
	 * @param $service
	 * @return mixed
	 */
	public function service($service): mixed
	{
		$classname = "Google_Service_$service";

		return new $classname($this->client);
	}

	/**
	 * @param array|null $config
	 * @throws GoogleClientInvalidConfiguration
	 */
	protected function guardAgainstInvalidConfiguration(array $config = null)
	{
		if (empty($config['calendar_id'])) {
			throw GoogleClientInvalidConfiguration::calendarIdNotSpecified();
		}

		$authProfile = $config['default_auth_profile'];

		if ($authProfile === 'service_account') {
			$this->validateServiceAccountConfigSettings($config);

			return;
		}

		if ($authProfile === 'oauth') {
			$this->validateOAuthConfigSettings($config);

			return;
		}

		throw GoogleClientInvalidConfiguration::invalidAuthenticationProfile($authProfile);
	}

	/**
	 * @param array|null $config
	 * @throws GoogleClientInvalidConfiguration
	 */
	protected function validateServiceAccountConfigSettings(array $config = null)
	{
		$credentials = $config['auth_profiles']['service_account']['credentials_json'];

		$this->validateConfigSetting($credentials);
	}

	/**
	 * @param array|null $config
	 * @throws GoogleClientInvalidConfiguration
	 */
	protected function validateOAuthConfigSettings(array $config = null)
	{
		$credentials = $config['auth_profiles']['oauth']['credentials_json'];

		$this->validateConfigSetting($credentials);

		$token = $config['auth_profiles']['oauth']['token_json'];

		$this->validateConfigSetting($token);
	}

	/**
	 * @param string $setting
	 * @throws GoogleClientInvalidConfiguration
	 */
	protected function validateConfigSetting(string $setting)
	{
		if (!is_array($setting) && !is_string($setting)) {
			throw GoogleClientInvalidConfiguration::credentialsTypeWrong($setting);
		}

		if (is_string($setting) && !file_exists($setting)) {
			throw GoogleClientInvalidConfiguration::credentialsJsonDoesNotExist($setting);
		}
	}

	/**
	 * @param $method
	 * @param $args
	 * @return false|mixed
	 * @throws Exception
	 */
	public function __call($method, $args)
	{
		if (!method_exists($this->client, $method)) {
			throw new Exception("Call to undefined method '{$method}'");
		}

		return call_user_func_array([$this->client, $method], $args);
	}
}
