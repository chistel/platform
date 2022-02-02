<?php
namespace Tests\View;

use tests\TestCase;

class ViewTest extends TestCase
{
    function test_it_can_retrieve_method_output_via_object_properties()
    {
        $view = new ViewStub;

        // Call property twice, this should only call the method once
        $view->property;
        $view->property;

        $this->assertEquals(1, $view->called);
    }

    function test_it_can_return_all_method_values_and_cache_them()
    {
        $this->assertEquals(['property' => 'result'], (new ViewStub)->toArray());
    }
}

class ViewStub extends \Platform\View\View
{
    public $called = 0;

    public function property()
    {
        $this->called++;

        return 'result';
    }

    public function setProperty()
    {
        // Not included in array
    }
}
