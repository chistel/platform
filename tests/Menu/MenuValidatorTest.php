<?php
namespace Tests\Menu;

use Platform\Menu\Item;
use Platform\Menu\Menu;
use Platform\Menu\MenuValidator;
use Tests\TestCase;

class MenuValidatorTest extends TestCase
{
    public function testItValidatesCorrectData()
    {
        $menu = new Menu('main');
        $menu->addChild(new Item('item-1', '', ''));

        $submenu = new Menu('submenu', '', '');
        $submenu->addChild(new Item('item-3', '', ''));
        $submenu->addChild(new Item('item-4', '', ''));
        $menu->addChild($submenu);

        $validator = new MenuValidator($menu);

        $this->assertTrue($validator->valid());
    }

    public function testItFailsForSpaces()
    {
        $menu = new Menu('main');
        $menu->addChild(new Item('foo', '', ''));
        $menu->addChild(new Item('foo-bar', '', ''));
        $menu->addChild(new Item('lorem ipsum', '', ''));

        $validator = new MenuValidator($menu);

        $this->assertFalse($validator->valid());
        $this->assertEquals('["lorem ipsum"]', $validator->errorMessage());
    }

    public function testItDetectsEmptyNames()
    {
        $menu = new Menu('main');
        $menu->addChild(new Item('item-1', '', ''));
        $menu->addChild(new Item('', '', ''));
        $menu->addChild(new Item('item-3', '', ''));

        $validator = new MenuValidator($menu);

        $this->assertFalse($validator->valid());
        $this->assertEquals('[""]', $validator->errorMessage());
    }

    public function testItDetectsDuplicateNames()
    {
        $menu = new Menu('main');
        $menu->addChild(new Item('item-1', '', ''));
        $menu->addChild(new Item('item-1', '', ''));

        $submenu = new Menu('submenu', '', '');
        $submenu->addChild(new Item('item-3', '', ''));
        $submenu->addChild(new Item('item-3', '', ''));
        $submenu->addChild(new Item('item-4', '', ''));
        $menu->addChild($submenu);

        $validator = new MenuValidator($menu);

        $this->assertFalse($validator->valid());
        $this->assertEquals('["item-1","item-3"]', $validator->errorMessage());
    }
}
