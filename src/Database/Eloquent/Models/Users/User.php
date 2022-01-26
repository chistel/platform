<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           User.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:19 PM
 */

namespace Platform\Database\Eloquent\Models\Users;

use Platform\Abstracts\AuthenticationModel;
use Platform\Traits\Common\HasWallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 *
 * @package Platform\Models\Users
 */
class User extends AuthenticationModel implements HasMedia
{
    use HasRoles;
    use HasWallet;
    use InteractsWithMedia;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'last_seen',
        'username',
        'first_name',
        'last_name',
        'phone',
        'intl_phone',
        'phone_verified_at',
        'email',
        'email_verified_at',
        'password',
        'ref_code',
        'referral_code_click',
        'current_referral_level_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_seen' => 'datetime',
    ];
    /**
     * @var string[]
     */
    protected $appends = [
        'user_avatar',
        'full_name',
    ];

    /**
     * Model's boot function
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function (self $user) {
            if (!is_null($user->password)) {
                // Hash user password, if not already hashed
                if (Hash::needsRehash($user->password)) {
                    $user->password = Hash::make($user->password);
                }
            }
            if (is_null($user->ref_code)) {
                $user->ref_code = (new User())->generateReferral();
            }
        });
    }

    /**
     * Get the customer full name.
     */
    public function getFullNameAttribute(): string
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    /**
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->getFullNameAttribute();
    }

    /**
     * @param Media|null $media
     */
    public function registerMediaConversions(Media $media = NULL): void
    {
        try {
            $this->addMediaConversion('small')
                ->width(120)
                ->height(120);

            $this->addMediaConversion('medium')
                ->width(200)
                ->height(200);
            $this->addMediaConversion('large')
                ->width(480);
            //->height(120);
        } catch (InvalidManipulation $e) {
        }
    }


    /**
     * Route notifications for the Africas Talking channel.
     *
     * @param Notification $notification
     * @return string
     */
    public function routeNotificationForAfricasTalking($notification)
    {
        return $this->intl_phone;
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPassword($token));
    }

    /**
     * @return bool
     */
    public function hasVerifiedPhone(): bool
    {
        return !is_null($this->phone_verified_at);
    }


    /**
     * @return bool
     */
    public function markPhoneAsVerified(): bool
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * @return MorphMany
     */
    public function withdrawal_setups(): MorphMany
    {
        return $this->morphMany(WithdrawalSetup::class, 'withdrawable');
    }

    /**
     * @param Builder $query
     * @param $referral
     * @return bool
     */
    public function scopeReferralExists(Builder $query, $referral): bool
    {
        return $query->where(function ($query) use ($referral) {
            $query->where('ref_code', $referral)->orWhere('username', $referral);
        })->exists();
    }

    /**
     * @return string
     */
    public function generateReferral(): string
    {
        $length = 5;
        do {
            $referral = Str::random($length);
        } while ($this->referralExists($referral));

        return $referral;
    }
    /**
     * A user has a referrer.
     *
     * @return HasOne
     */
    public function referrer(): HasOne
    {
        return $this->hasOne(Referral::class, 'user_id', 'id');
    }

    /**
     * A user has many referrals.
     *
     * @return HasMany
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class, 'referral_id', 'id');
    }

    /**
     * @return string|null
     */
    public function user_image(): ?string
    {
        $avatarUrl = '';
        $mediaItem = $this->getFirstMediaUrl('profile_images');
        if (!is_null($mediaItem) && !empty($mediaItem)) {
            $avatarUrl = $mediaItem;
        }
        if (!empty($avatarUrl) && @getimagesize($avatarUrl)) {
            return $avatarUrl;
        }
        return asset('assets/svg/user_r.svg');
    }


    /**
     * @return string|null
     */
    public function getUserAvatarAttribute(): ?string
    {
        return $this->user_image();
    }
}
