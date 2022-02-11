<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Menu.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 7:22 PM
 */

namespace Platform\Menu;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Menu extends MenuItem implements Arrayable, Jsonable
{
    /**
     * Stores the name for the menu. This used as a reference point for menu items (where
     * necessary) as well as when rendering specific required menus.
     *
     * @var string|null
     */
    public ?string $name;

    /**
     * Construct a new menu with the following text.
     *
     * @param string $name
     * @param string|null $text
     * @param null   $icon
     */
    public function __construct(string $name, string $text = null, $icon = null)
    {
        $this->name = strtolower($name);
        $this->text = strip_tags($text);;
        $this->icon = $icon;
    }

    /**
     * Add a new child menu item, and set its parent.
     *
     * @param Item $item
     */
    public function addChild(MenuItem $item)
    {
        $this->children[] = $item;

        // Menus are only renderable if they have children
        $this->renderable = true;

        $item->setParent($this);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'text' => $this->text,
            'icon' => $this->icon,
            'active' => $this->isActive(),
            'children' => $this->children ?
                array_map(function ($child) {
                    return $child->toArray();
                }, $this->children()) : [],
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }
}
