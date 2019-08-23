<?php


namespace InfoBundle\Services;


use App\Interfaces\MenuInterface;
use Symfony\Component\Templating\EngineInterface;

class Menu implements MenuInterface
{
    /**
     * @var EngineInterface
     */
    private $twig;

    public function __construct(EngineInterface $twig)
    {
        $this->twig = $twig;
    }

    public function getEntries(): string
    {
        return $this->twig->render('closed_area/Info/menu.html.twig');
    }
}