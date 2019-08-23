<?php


namespace App\Services;


use App\Interfaces\MenuInterface;

class Menu
{


    /**
     * @var \App\Interfaces\MenuInterface[]
     */
    private $menus;

    public function __construct(iterable $menus)
    {
        $this->menus = $menus;
    }

    public function getRenderedMenu(): string
    {
        $menuString = '';
        foreach ($this->menus as $menu) {
            $menuString .= $menu->getEntries();
        }
        return $menuString;
    }
}