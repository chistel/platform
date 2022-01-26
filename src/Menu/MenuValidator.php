<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           MenuValidator.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 7:22 PM
 */

namespace Platform\Menu;

use Illuminate\Support\Collection;

class MenuValidator
{
    /** @var Menu */
    private Menu $menu;

    /** @var array */
    private array $invalidNames = [];

    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    /**
     * Check whether all menu items and its children have names assigned and they are are unique.
     *
     * @return bool
     */
    public function valid(): bool
    {
        $menuItemNames = $this->extractMenuNamesRecursively($this->menu);
        $filteredNames = $menuItemNames->filter()->unique()->map(function ($name) {
            return str_replace(' ', '', strtolower($name));
        });

        $this->invalidNames = $menuItemNames->diffAssoc($filteredNames)->values()->toArray();

        return $menuItemNames->count() === $filteredNames->count() && $this->invalidNames === [];
    }

    /**
     * List of menu item names that failed validation.
     *
     * @return string
     */
    public function errorMessage(): string
    {
        return json_encode($this->invalidNames);
    }

    private function extractMenuNamesRecursively(MenuItem $item): Collection
    {
        $names = collect([$item->name]);
        foreach ($item->children() as $child) {
            $names->push($this->extractMenuNamesRecursively($child));
        }
        return $names->flatten();
    }
}
