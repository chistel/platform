<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Credentials.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:27 PM
 */

namespace Platform\Services\Connected;

use DateTime;
use Exception;
use JsonSerializable;
use DateTimeInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Platform\Database\Eloquent\Models\Common\ConnectedAccount;
use Platform\Contracts\ConnectedAccounts\Credentials as CredentialsContract;

class Credentials implements CredentialsContract, Arrayable, Jsonable, JsonSerializable
{
    /**
     * The credentials' user ID.
     *
     * @var string
     */
    protected string $id;

    /**
     * The credentials token.
     *
     * @var string
     */
    protected $token;

    /**
     * The credentials token secret.
     *
     * @var string|null
     */
    protected string|null $tokenSecret;

    /**
     * The credentials refresh token.
     *
     * @var string|null
     */
    protected string|null $refreshToken;

    /**
     * The credentials' expiry.
     *
     * @var DateTimeInterface|null
     */
    protected DateTimeInterface|null $expiry;

    /**
     * Create a new credentials instance.
     *
     * Credentials constructor.
     *
     * @param  ConnectedAccount  $connectedAccount
     */
    public function __construct(ConnectedAccount $connectedAccount)
    {
        $this->id = $connectedAccount->provider_id;
        $this->token = $connectedAccount->token;
        $this->tokenSecret = $connectedAccount->secret;
        $this->refreshToken = $connectedAccount->refresh_token;
        $this->expiry = $connectedAccount->expires_at;
    }

    /**
     * Get the ID for the credentials.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get token for the credentials.
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Get the token secret for the credentials.
     *
     * @return string|null
     */
    public function getTokenSecret(): ?string
    {
        return $this->tokenSecret;
    }

    /**
     * Get the refresh token for the credentials.
     *
     * @return string|null
     */
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * Get the expiry date for the credentials.
     *
     * @return DateTime|DateTimeInterface|null
     * @throws Exception
     */
    public function getExpiry(): DateTime|DateTimeInterface|null
    {
        if (is_null($this->expiry)) {
            return null;
        }

        return new DateTime($this->expiry);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     * @throws Exception
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'token' => $this->getToken(),
            'token_secret' => $this->getTokenSecret(),
            'refresh_token' => $this->getRefreshToken(),
            'expiry' => $this->getExpiry(),
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return array
     * @throws Exception
     */
    public function toJson($options = 0)
    {
        return $this->toArray();
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array
     * @throws Exception
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the object instance to a string.
     *
     * @return string
     * @throws Exception
     */
    public function __toString()
    {
        return json_encode($this->toJson());
    }
}
