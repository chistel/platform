<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Referral.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:24 PM
 */

namespace Platform\Database\Eloquent\Models\Common;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Platform\Abstracts\BaseModel;
use Platform\Database\Eloquent\Models\Users\User;

/**
 * Class Referral
 *
 * @package Platform\Models\Common
 */
class Referral extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'referrals';
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'referral_paid',
        'referral_id',
        'referral_balance',
        'ip_address',
        'referral_level_id'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referral_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function referred(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function referralLevel(): BelongsTo
    {
        return $this->belongsTo(ReferringLevel::class, 'referral_level_id');
    }

    /**
     * @return HasMany
     */
    public function bonuses(): HasMany
    {
        return $this->hasMany(ReferralBonus::class, 'referral_id', 'id');
    }

    /**
     * @return mixed
     */
    public function allEarning(): mixed
    {
        return $this->bonuses()->sum('amount');
    }
}
