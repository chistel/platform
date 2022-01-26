<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Currency.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:10 PM
 */

namespace Platform\Database\Eloquent\Models\Common;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Platform\Abstracts\BaseModel;

class Currency extends BaseModel
{
    /**
     * Sortable columns.
     *
     * @var array
     */
    public array $sortable = ['name', 'code', 'rate', 'enabled'];
    /**
     * @var string
     */
    protected $table = 'all_currencies';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'symbol',
    ];

    /**
     * Set currency code in capital
     *
     * @param $code
     * @return void
     */
    public function setCodeAttribute($code)
    {
        $this->attributes['code'] = strtoupper($code);
    }

    /**
     * Get the exchange rate associated with the currency.
     */
    public function exchangeRate(): HasOne
    {
        return $this->hasOne(CurrencyExchangeRate::class, 'target_currency');
    }
}
