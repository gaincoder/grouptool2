<?php


namespace App\Services;


use App\Interfaces\WidgetInterface;

class Widget
{


    /**
     * @var WidgetInterface[]
     */
    private $widgets;

    public function __construct(iterable $widgets)
    {
        $this->widgets = $widgets;
    }

    public function getRenderedWidgets(): string
    {
        $widgetString = '';
        foreach ($this->widgets as $widget) {
            $widgetString .= $widget->getEntries();
        }
        return $widgetString;
    }
}