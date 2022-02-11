<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           MenuItem.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 7:22 PM
 */

namespace Platform\Menu;

abstract class MenuItem
{
    /**
     * The name item that will be used as id attribute.
     *
     * @var string|null It is possible for top-level menus to not need any text.
     */
    public ?string $name;

    /**
     * The text of the menu item.
     *
     * @var string|null It is possible for top-level menus to not need any text.
     */
    public ?string $text;

    /**
     * Stores the parent Menu.
     *
     * @var Menu
     */
    protected $parent;

    /**
     * Defines whether the menu item is renderable.
     *
     * @var bool
     */
    protected bool $renderable = false;

    /**
     * Stores the children this menu item has.
     *
     * @var array
     */
    protected array $children = [];

    /**
     * Determines whether the menu or item is active.
     *
     * @var bool
     */
    private bool $active = false;

    /**
     * The icon class to be used for the menu item. If an icon is specified,
     * then the appropriate icon will be displayed to the left o the text
     * on the menu.
     *
     * @var null|string
     */
    protected ?string $icon;

    /** @var array */
    protected array $attributes = [];

    /**
     * Determines whether the menu item has a parent.
     *
     * @return bool
     */
    public function hasParent(): bool
    {
        return (bool) $this->parent;
    }

    /**
     * Determines whether the item has children.
     *
     * @return bool
     */
    public function hasChildren(): bool
    {
        return (bool) count($this->children);
    }

    /**
     * Returns the array of children this item has.
     *
     * @return array
     */
    public function children(): array
    {
        return $this->children;
    }

    /**
     * Inverse check of hasChildren. Helper method.
     *
     * @return bool
     */
    public function isParent(): bool
    {
        return $this->hasChildren();
    }

    /**
     * @return bool
     */
    public function isRenderable(): bool
    {
        return $this->renderable;
    }

    /**
     * Set the parent of the menu item.
     *
     * @param Menu $parent
     */
    public function setParent(Menu $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Return the icon string.
     *
     * @return null|string
     */
    public function icon(): ?string
    {
        return $this->icon;
    }

    /**
     * Returns the active state of the item.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Sets the active property on the item. This is useful for highlighting/activating menu items visually.
     */
    public function setActive()
    {
        $this->active = true;

        if ($this->hasParent()) {
            $this->parent->setActive();
        }
    }

    public function attributes(array $merge = []): string
    {
        $attributes = array_merge($this->attributes, $merge);

        return collect($attributes)->map(function ($value, $key) {
            return "{$key}=\"{$value}\"";
        })->implode(' ');
    }
}
