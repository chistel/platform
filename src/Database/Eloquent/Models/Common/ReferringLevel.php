<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           ReferringLevel.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:24 PM
 */

namespace Platform\Database\Eloquent\Models\Common;

use Platform\Abstracts\BaseModel;
use Platform\Traits\Common\HasSlug;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Sluggable\SlugOptions;

class ReferringLevel extends BaseModel
{
	use HasSlug;
	/**
	 * @var string
	 */
	protected $table = 'referring_levels';

	/**
	 * @var string[]
	 */
	protected $fillable = [
		'slug',
		'enabled',
		'is_intro',
		'next_level_id',
		'title',
		'min_referral',
		'max_referral',
		'percentage',
		'percentage_value_cap'
	];

	/**
	 * Get the options for generating the slug.
	 */
	public function getSlugOptions() : SlugOptions
	{
		return SlugOptions::create()
			->generateSlugsFrom('title')
			->saveSlugsTo('slug');
	}
	/**
	 * @return HasOne
	 */
	public function nextLevel(): HasOne
	{
		return $this->hasOne(ReferringLevel::class,'next_level_id');
	}

	/**
	 * @return HasMany
	 */
	public function referrals(): HasMany
	{
		return $this->hasMany(Referral::class,'referral_level_id');
	}
}
