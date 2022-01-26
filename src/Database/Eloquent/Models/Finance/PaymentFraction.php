<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           PaymentFraction.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:53 PM
 */

namespace Platform\Database\Eloquent\Models\Finance;

use Platform\Abstracts\BaseModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PaymentFraction extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'payment_fractions';
    /**
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * @var string[]
     */
    protected $fillable = [
        'can_be_requested',
        'status',
        'amount',
        'payout_request_id',
        'final_amount',
        'taken_commission_type',
        'taken_commission_value',
        'ownerable_type',
        'ownerable_id',
        'sourceable_type',
        'sourceable_id',
        'approved_at',
        'declined_at',
        'cancelled_at',
        'refunded_at',
    ];

    protected $dates = [
        'approved_at',
        'declined_at',
        'cancelled_at',
        'refunded_at',
    ];

    /**
     * @return MorphTo
     */
    public function ownerable()
    {
        return $this->morphTo();
    }

    /**
     * @return MorphTo
     */
    public function sourceable()
    {
        return $this->morphTo();
    }
}
