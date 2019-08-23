<?php


namespace PollBundle\Services;


use PollBundle\Entity\Poll;
use App\Interfaces\WidgetInterface;
use Doctrine\ORM\EntityManagerInterface;
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


        $pollRepo = $this->em->getRepository(Poll::class);
        $params['polls'] = $pollRepo->findTopFive($this->security->isGranted('ROLE_STAMMI'));

        return $this->twig->render('closed_area/Poll/widget.html.twig', $params);
    }
}