<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Item.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 7:22 PM
 */

namespace Platform\Menu;

use Illuminate\Contracts\Support\Arrayable;

class Item extends MenuItem implements Arrayable
{
    /**
     * The link that when a user clicks, will be directed to.
     *
     * @var string
     */
    public string $link;

    private ?string $target;

    /**
     * Construct a new item to be appended to a menu.
     *
     * @param string $name will be used as id attribute
     * @param string $text
     * @param string $link
     * @param string|null $icon
     * @param string|null $target
     * @param array $attributes
     */
    public function __construct(string $name, string $text, string $link, string $icon = null, string $target = null, array $attributes = [])
    {
        $this->name = strip_tags(strtolower($name));
        $this->text = strip_tags($text);
        $this->link = $link;
        $this->attributes = $attributes;

        $this->renderable = true;
        $this->icon = $icon;
        $this->target = $target;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'text' => $this->text,
            'name' => $this->name,
            'link' => $this->link,
            'icon' => $this->icon(),
            'active' => $this->isActive(),
            'target' => $this->target,
        ];
    }
}
