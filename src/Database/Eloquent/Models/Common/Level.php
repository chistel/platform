<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Level.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:10 PM
 */

namespace Platform\Database\Eloquent\Models\Common;

use Platform\Abstracts\BaseModel;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * Class Level
 *
 * @package Platform\Models\Common
 */
class Level extends BaseModel
{
    use HasSlug;

    protected $table = 'levels';

    protected $fillable = [
        'name',
        'slug',
        'sort',
        'enabled'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
