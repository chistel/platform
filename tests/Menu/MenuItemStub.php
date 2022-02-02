<?php
namespace Tests\Menu;

use Platform\Menu\MenuItem;

class MenuItemStub extends MenuItem
{
    public function __construct($name, $text, array $attributes = [])
    {
        $this->name = $name;
        $this->text = $text;
        $this->attributes = $attributes;
    }
}
