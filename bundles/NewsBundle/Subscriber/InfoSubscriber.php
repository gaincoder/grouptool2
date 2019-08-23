<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 22:52
 */

namespace NewsBundle\Subscriber;


use NewsBundle\Entity\News;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use InfoBundle\Event\AbstractInfoEvent;
use InfoBundle\Event\InfoCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;


class InfoSubscriber implements EventSubscriberInterface
{


    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var RouterInterface
     */
    private $router;


    public function __construct(EntityManagerInterface $em, RouterInterface $router)
    {
        $this->em = $em;
        $this->router = $router;

    }


    public static function getSubscribedEvents()
    {
        return [
            InfoCreatedEvent::class => 'onInfoCreated'
        ];
    }

    public function onInfoCreated(AbstractInfoEvent $event)
    {
        $user = $event->getUser();
        $info = $event->getInfo();

        $url = $this->router->generate('info', [], Router::ABSOLUTE_URL);

        $news = new News('Neue Info erstellt', ucfirst($user->getUsername()) . ' hat "' . '<a href=\'' . $url . '#' . $info->id . '\'>' . $info->headline
            . '</a>" erstellt', $info->permission);

        $this->em->persist($news);
        $this->em->flush();

    }
}