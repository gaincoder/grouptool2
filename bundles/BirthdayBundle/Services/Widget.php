<?php


namespace BirthdayBundle\Services;


use App\Interfaces\WidgetInterface;
use BirthdayBundle\Entity\Birthday;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Templating\EngineInterface;

class Widget implements WidgetInterface
{

    /**
     * @var EngineInterface
     */
    private $twig;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EngineInterface $twig, EntityManagerInterface $em)
    {
        $this->twig = $twig;
        $this->em = $em;
    }

    public function getEntries(): string
    {
        $params = array();
        $birthdayRepo = $this->em->getRepository(Birthday::class);
        $params['birthday_current_month'] = $birthdayRepo->findAllThisMonthOrderedByDay();
        $params['birthday_next_month'] = $birthdayRepo->findAllNextMonthOrderedByDay();


        return $this->twig->render('closed_area/Birthday/widget.html.twig', $params);
    }
}