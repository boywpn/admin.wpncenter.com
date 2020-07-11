<?php

namespace Arrilot\Widgets\Test;

use Arrilot\Widgets\Test\Support\TestApplicationWrapper;
use Arrilot\Widgets\Test\Support\TestCase;
use Arrilot\Widgets\WidgetGroup;

class WidgetGroupTest extends TestCase
{
    /**
     * @var WidgetGroup
     */
    protected $widgetGroup;

    public function setUp()
    {
        $this->widgetGroup = new WidgetGroup('key1', new TestApplicationWrapper());
    }

    public function testItCanDisplayWidgets()
    {
        $this->widgetGroup->addWidget('Slider', ['slides' => 5]);
        $this->widgetGroup->addAsyncWidget('Slider');

        $output = $this->widgetGroup->display();

        $this->assertEquals(
            'Slider was executed with $slides = 5 foo: bar'.
            '<div id="arrilot-widget-container-2" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
                '<script type="text/javascript">'.
                    'var widgetTimer2 = setInterval(function() {'.
                        'if (window.$) {'.
                            "$('#arrilot-widget-container-2').load('".$this->ajaxUrl('Slider', [], 2)."');".
                            'clearInterval(widgetTimer2);'.
                        '}'.
                    '}, 100);'.
                '</script>'.
            '</div>', $output);
    }

    public function testItCanSetAndResetPosition()
    {
        $this->widgetGroup->addWidget('Slider', ['slides' => 5, 'foo' => 'Taylor']);
        $this->widgetGroup->position(50)->addWidget('Slider');

        $output = $this->widgetGroup->display();

        $this->assertEquals('Slider was executed with $slides = 6 foo: bar'.
            'Slider was executed with $slides = 5 foo: Taylor', $output);
        $this->assertEquals(100, $this->widgetGroup->getPosition());
    }

    public function testMultipleWidgetGroupsCanExistTogether()
    {
        $this->widgetGroup->addWidget('Slider', ['slides' => 5, 'foo' => 'Taylor']);
        $this->widgetGroup->position(50)->addWidget('Slider');

        $widgetGroup2 = new WidgetGroup('key2', new TestApplicationWrapper());
        $widgetGroup2->position(40)->addWidget('Slider', ['slides' => 10]);
        $widgetGroup2->position(40)->addWidget('Slider', ['slides' => 15]);

        $output = $this->widgetGroup->display();
        $output2 = $widgetGroup2->display();

        $this->assertEquals('Slider was executed with $slides = 6 foo: bar'.
            'Slider was executed with $slides = 5 foo: Taylor', $output);
        $this->assertEquals(100, $this->widgetGroup->getPosition());

        $this->assertEquals('Slider was executed with $slides = 10 foo: bar'.
            'Slider was executed with $slides = 15 foo: bar', $output2);
        $this->assertEquals(100, $widgetGroup2->getPosition());
    }

    public function testSeparator()
    {
        $this->widgetGroup->addWidget('Slider', ['slides' => 5]);
        $this->widgetGroup->addAsyncWidget('Slider');

        $output = $this->widgetGroup->setSeparator('<hr>')->display();

        $this->assertEquals(
            'Slider was executed with $slides = 5 foo: bar'.
            '<hr>'.
            '<div id="arrilot-widget-container-2" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
                '<script type="text/javascript">'.
                    'var widgetTimer2 = setInterval(function() {'.
                        'if (window.$) {'.
                            "$('#arrilot-widget-container-2').load('".$this->ajaxUrl('Slider', [], 2)."');".
                            'clearInterval(widgetTimer2);'.
                        '}'.
                    '}, 100);'.
                '</script>'.
            '</div>', $output);
    }

    public function testWrap()
    {
        $this->widgetGroup->addWidget('Slider', ['slides' => 5]);
        $this->widgetGroup->addAsyncWidget('Slider');

        $output = $this->widgetGroup->wrap(function ($content, $index, $count) {
            return "<div class='widget widget-{$index}-{$count}'>{$content}</div>";
        })->display();

        $this->assertEquals(
            '<div class=\'widget widget-0-2\'>Slider was executed with $slides = 5 foo: bar</div>'.
            '<div class=\'widget widget-1-2\'><div id="arrilot-widget-container-2" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
                '<script type="text/javascript">'.
                    'var widgetTimer2 = setInterval(function() {'.
                        'if (window.$) {'.
                            "$('#arrilot-widget-container-2').load('".$this->ajaxUrl('Slider', [], 2)."');".
                            'clearInterval(widgetTimer2);'.
                        '}'.
                    '}, 100);'.
                '</script>'.
            '</div></div>', $output);
    }

    public function testIsEmpty()
    {
        $this->assertTrue($this->widgetGroup->isEmpty());

        $this->widgetGroup->addWidget('Slider');

        $this->assertFalse($this->widgetGroup->isEmpty());
    }

    public function testAny()
    {
        $this->assertFalse($this->widgetGroup->any());

        $this->widgetGroup->addWidget('Slider');

        $this->assertTrue($this->widgetGroup->any());
    }

    public function testCount()
    {
        $this->assertSame(0, $this->widgetGroup->count());

        $this->widgetGroup->addWidget('Slider');
        $this->assertSame(1, $this->widgetGroup->count());

        $this->widgetGroup->position(50)->addWidget('Slider');
        $this->assertSame(2, $this->widgetGroup->count());

        $this->widgetGroup->position(50)->addWidget('Slider');
        $this->assertSame(3, $this->widgetGroup->count());
    }

    public function testRemoveById()
    {
        $id1 = $this->widgetGroup->addWidget('Slider');
        $id2 = $this->widgetGroup->addWidget('Slider');
        $this->assertSame(2, $this->widgetGroup->count());

        $this->widgetGroup->removeById($id1);
        $this->assertSame(1, $this->widgetGroup->count());

        $this->widgetGroup->removeById($id2);
        $this->assertSame(0, $this->widgetGroup->count());
    }

    public function testRemoveByName()
    {
        $this->widgetGroup->addWidget('Slider');
        $this->widgetGroup->addWidget('TestDefaultSlider');
        $this->widgetGroup->addWidget('Slider');
        $this->assertSame(3, $this->widgetGroup->count());

        $this->widgetGroup->removeByName('Slider');
        $this->assertSame(1, $this->widgetGroup->count());

        $this->widgetGroup->removeByName('TestDefaultSlider');
        $this->assertSame(0, $this->widgetGroup->count());
    }

    public function testRemoveByPosition()
    {
        $this->widgetGroup->position(60)->addWidget('Slider');
        $this->widgetGroup->position(50)->addWidget('TestDefaultSlider');
        $this->widgetGroup->position(50)->addWidget('Slider');
        $this->assertSame(3, $this->widgetGroup->count());

        $this->widgetGroup->removeByPosition(50);
        $this->assertSame(1, $this->widgetGroup->count());

        $this->widgetGroup->removeByPosition(60);
        $this->assertSame(0, $this->widgetGroup->count());
    }

    public function testRemoveAll()
    {
        $this->widgetGroup->addWidget('Slider');
        $this->widgetGroup->addWidget('TestDefaultSlider');
        $this->widgetGroup->addWidget('Slider');
        $this->assertSame(3, $this->widgetGroup->count());

        $this->widgetGroup->removeAll();
        $this->assertSame(0, $this->widgetGroup->count());
    }
}
