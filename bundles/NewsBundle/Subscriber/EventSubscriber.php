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
use EventBundle\Event\EventCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class EventSubscriber implements EventSubscriberInterface
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
            \EventBundle\Event\EventCreatedEvent::class => 'onEventCreated'
        ];
    }

    public function onEventCreated(EventCreatedEvent $event)
    {
        $user = $event->getUser();
        $event = $event->getEvent();
        $url = $this->router->generate('event_view', ['event' => $event->id], Router::ABSOLUTE_URL);
        $em = $this->em;
        $news = new News('Neue Veranstaltung erstellt', ucfirst($user->getUsername()) . ' hat <a href="'
            . $url . '">' . $event->name . '</a> am ' . $event->date->format('d.m.y') . " erstellt", $event->permission);

        $em->persist($news);
        $em->flush();


    }
}