<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           InteractsWithReview.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:27 PM
 */

namespace Platform\Traits\Common;


use Platform\Database\Eloquent\Models\Common\Review;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

trait InteractsWithReview
{
	/**
	 * Get all reviews of this model.
	 *
	 * @return MorphMany
	 */
	public function reviews()
	{
		return $this->morphMany(Review::class, 'reviewable');
	}

	/**
	 * Get the summarized score value.
	 *
	 * @return Collection
	 */
	public function getScoreAttribute()
	{
		return $this->reviews()->sum('score');
	}

	/**
	 * Get the average score value.
	 *
	 * @return int
	 */
	public function getAvgScoreAttribute()
	{
		return $this->reviews()->avg('score');
	}
}
