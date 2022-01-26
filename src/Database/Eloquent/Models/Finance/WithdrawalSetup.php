<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           WithdrawalSetup.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:55 PM
 */

namespace Platform\Database\Eloquent\Models\Finance;

use Platform\Abstracts\BaseModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WithdrawalSetup extends BaseModel
{
    protected $table = 'withdrawal_setups';

    protected $primaryKey = 'id';

    protected $fillable = [
        'enabled',
        'type',
        'withdrawable_id',
        'withdrawable_type',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    /**
     * @return MorphTo
     */
    public function withdrawable()
    {
        return $this->morphTo();
    }
}
