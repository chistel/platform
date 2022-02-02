<?php
namespace Tests\Menu;

use Tests\TestCase;

class MenuItemTest extends TestCase
{
    function test_text_assignment_is_correct()
    {
        $menuItem = new MenuItemStub('item-name', 'text');

        $this->assertEquals('item-name', $menuItem->name);
        $this->assertEquals('text', $menuItem->text);
    }

    public function test_attributes_are_formatted()
    {
        $menuItem = new MenuItemStub('item-name', 'text', ['class' => 'a class', 'id' => 'name']);

        $this->assertStringContainsString('class="a class"', $attributes = $menuItem->attributes(['extra' => 'value']));
        $this->assertStringContainsString('id="name"', $attributes);
        $this->assertStringContainsString('extra="value"', $attributes);
    }
}
