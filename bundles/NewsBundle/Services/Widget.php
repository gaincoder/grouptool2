<?php


namespace NewsBundle\Services;


use App\Interfaces\WidgetInterface;
use Doctrine\ORM\EntityManagerInterface;
use NewsBundle\Entity\News;
use Symfony\Component\Security\Core\Security;
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
    /**
     * @var Security
     */
    private $security;

    public function __construct(EngineInterface $twig, EntityManagerInterface $em, Security $security)
    {
        $this->twig = $twig;
        $this->em = $em;
        $this->security = $security;
    }

    public function getEntries(): string
    {
        $params = array();
        $newsRepo = $this->em->getRepository(News::class);
        $params['newslist'] = $newsRepo->findTopFive($this->security->getUser()->getGroups());

        return $this->twig->render('closed_area/News/widget.html.twig', $params);
    }
}