<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Wallet.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:19 PM
 */

namespace Platform\Database\Eloquent\Models\Common;


use Platform\Abstracts\BaseModel;
use Platform\Traits\Finance\BelongsToTransaction;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Wallet extends BaseModel
{
    use BelongsToTransaction;

    /**
     * @var string
     */
    protected $table = 'wallets';

    /**
     * @var string
     */
    protected $primaryKey = 'id';


    /**
     * @var array
     */
    protected $attributes = [
        'balance' => 0,
    ];

    protected $fillable = [
        'owner_id',
        'owner_type',
        'balance',
    ];

    protected $casts = [
        'balance' => 'float'
    ];

    /**
     * Retrieve owner
     */
    public function owner(): MorphTo
    {
        return $this->morphTo('owner', 'owner_type', 'owner_id');
    }


    /**
     * Determine if the user can withdraw the given amount
     *
     * @param null $amount
     * @return boolean
     */
    public function canWithdraw($amount = NULL)
    {
        return $amount ? abs($this->balance) >= abs($amount) : abs($this->balance) > 0;
    }
}
