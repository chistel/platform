<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           ConnectedAccount.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:10 PM
 */

namespace Platform\Database\Eloquent\Models\Common;

use Platform\Abstracts\BaseModel;
use Platform\Services\Connected\Credentials;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;

class ConnectedAccount extends BaseModel
{
    use HasTimestamps;

    protected $table = 'connected_accounts';
    /**
     * @var array
     */
    protected $fillable = [
        'provider_name',
        'provider_id',
        'token',
        'secret',
        'refresh_token',
        'expires_at'
    ];

    /**
     * Get the credentials used for authenticating services.
     *
     * @return Credentials
     */
    public function getCredentials(): Credentials
    {
        return new Credentials($this);
    }


    /**
     * @return MorphTo
     */
    public function connectable(): MorphTo
    {
        return $this->morphTo();
    }
}
