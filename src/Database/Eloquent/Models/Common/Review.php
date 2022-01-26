<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Review.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:55 PM
 */

namespace Platform\Database\Eloquent\Models\Common;

use Platform\Abstracts\BaseModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'reviews';
    /**
     * @var string[]
     */
    protected $fillable = [
        'comment',
        'title',
        'score',
        'status',
        'author_id',
        'author_type'
    ];

    /**
     * @return MorphTo
     */
    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return MorphTo
     */
    public function author(): MorphTo
    {
        return $this->morphTo('author');
    }
}
