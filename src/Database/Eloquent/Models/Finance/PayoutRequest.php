<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           PayoutRequest.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:55 PM
 */

namespace Platform\Database\Eloquent\Models\Finance;

use Platform\Abstracts\BaseModel;
use Platform\Traits\Finance\BelongsToTransaction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayoutRequest extends BaseModel
{
    use BelongsToTransaction;

    /**
     * @var string
     */
    protected $table = 'payout_requests';
    /**
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * @var string[]
     */
    protected $fillable = [
        'withdrawal_setup_id',
        'status',
        'amount',
        'completed',
        'completed_at',
        'final_amount',
        'decline_reason',
        'approved_at',
        'declined_at'
    ];
    /**
     * @var string[]
     */
    protected $casts = [
        'completed_at' => 'datetime',
        'approved_at' => 'datetime',
        'declined_at' => 'datetime'
    ];


    public function ownerable()
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function withdrawalSetup()
    {
        return $this->belongsTo(WithdrawalSetup::class, 'withdrawal_setup_id', 'id');
    }
}
