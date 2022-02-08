<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Manager.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 7:22 PM
 */

namespace Platform\Menu;

use Illuminate\Support\Collection;

class Manager extends Collection
{
    /**
     * Add a newly created menu to the manager.
     *
     * @param MenuItem $menuItem
     */
    public function register(MenuItem $menuItem)
    {
        $this->items[] = $menuItem;

        if ($menuItem->hasChildren()) {
            $this->registerChildren($menuItem);
        }
    }

    /**
     * Return a specific menu.
     *
     * @param $menuName
     * @return mixed
     */
    public function menu($menuName)
    {
        return $this->filter(function ($menuItem) use ($menuName) {
            return $menuItem instanceof Menu && $menuItem->name == $menuName;
        })->first();
    }

    /**
     * @param $url
     */
    public function activateByUrl($url)
    {
        $activated = $this->activateByFullMatch($url);

        if (!$activated) {
            // When no active item is found, we'll try and make a partial match
            $this->activateByPartialMatch($url);
        }
    }

    /**
     * Set the current range of items to an active state based on the full url.
     *
     * @param string $url
     * @return bool
     */
    public function activateByFullMatch($url): bool
    {
        foreach ($this->itemsOnly() as $item) {
            if ($item->link == $url) {
                $item->setActive();

                return true;
            }
        }

        return false;
    }

    /**
     * Activate a menu based on a partial match.
     *
     * @param string $url
     */
    public function activateByPartialMatch($url)
    {
        $this->itemsOnly()->each(function ($menuItem) use ($url) {
            if (str_starts_with($url, $menuItem->link)) {
                $menuItem->setActive();
            }
        });
    }

    /**
     * Return only the items of the menu items, not the menus.
     *
     * @return Collection
     */
    protected function itemsOnly(): Collection
    {
        return $this->filter(function ($menuItem) {
            return $menuItem instanceof Item;
        });
    }

    /**
     * Register all children of the menu item.
     *
     * @param MenuItem $menuItem
     */
    protected function registerChildren(MenuItem $menuItem)
    {
        foreach ($menuItem->children() as $child) {
            $this->register($child);
        }
    }

    /**
     * Return JSON menu.
     *
     * @param string $menu
     * @return false|string|void
     */
    public function json(string $menu)
    {
        $menu = $this->menu($menu);

        if ($menu && $menu->hasChildren()) {
            return json_encode(collect($menu->children()));
        }
    }
}
