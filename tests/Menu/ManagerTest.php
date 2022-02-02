<?php
namespace Tests\Menu;

use Platform\Menu\Item;
use Platform\Menu\Manager;
use Platform\Menu\Menu;
use Tests\TestCase;

class ManagerTest extends TestCase
{
    /** @var Manager */
    private $manager;

    /** @var Item */
    private $item;

    /** @var Menu */
    private $menu;

    function init()
    {
        $this->item = new Item('menu-item', 'Item', '/item');

        $this->menu = new Menu('menu1', 'Top menu');
        $this->menu->addChild($this->item);

        $this->manager = new Manager;
        $this->manager->register($this->menu);
    }

    function test_it_can_activate_menu_items_based_on_the_url_via_partial_match()
    {
        $this->manager->activateByUrl('/items');

        $activeMenu = $this->manager->menu('menu1');

        $this->assertTrue($activeMenu->isActive());
        $this->assertTrue($activeMenu->hasChildren());
        $this->assertTrue($activeMenu->isParent());
        $this->assertTrue($activeMenu->children()[0]->isActive());
    }

    function test_it_can_activate_menus_on_full_matches()
    {
        $this->manager->activateByUrl('/item');

        $activeMenu = $this->manager->menu('menu1');

        $this->assertTrue($activeMenu->isActive());
    }

    function test_menu_item_getters()
    {
        $menuItem = new Item('name', 'Text', 'link', 'icon');

        $this->assertTrue($menuItem->isRenderable());
        $this->assertSame('icon', $menuItem->icon());
    }

    function test_json_menu()
    {
        $this->assertNull($this->manager->json('menu2'));

        $this->assertSame(
            '[{"text":"Item","name":"menu-item","link":"\/item","icon":null,"target":null}]',
            $this->manager->json('menu1')
        );
    }
}
