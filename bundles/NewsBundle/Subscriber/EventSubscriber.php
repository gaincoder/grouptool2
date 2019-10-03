<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 22:52
 */

namespace NewsBundle\Subscriber;


use EventBundle\Event\AbstractEventEvent;
use EventBundle\Event\EventDeletedEvent;
use EventBundle\Event\EventEditedEvent;
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
    private static $headline = 'Neue Aktivität erstellt';
    private static $text = '%s hat <a href="%s">%s</a> am %s erstellt';

    public function __construct(EntityManagerInterface $em, RouterInterface $router)
    {
        $this->em = $em;
        $this->router = $router;

    }


    public static function getSubscribedEvents()
    {
        return [
            EventCreatedEvent::class => 'onEventCreated',
            EventEditedEvent::class => 'onEventEdited',
            EventDeletedEvent::class => 'onEventDeleted'
        ];
    }


    public function onEventCreated(AbstractEventEvent $event)
    {
        $user = $event->getUser();
        $event = $event->getEvent();
        if($event->date instanceof \DateTime){
            $url = $this->router->generate('event_view', ['event' => $event->id], Router::ABSOLUTE_URL);

            $news = new News();
            $news->headline = self::$headline;
            $news->text = sprintf(self::$text, ucfirst($user->getUsername()), $url, $event->name, $event->getFormattedDate('d.m.y'));
            $news->referenceType = get_class($event);
            $news->referenceId = $event->id;
            $news->group = $event->group;

            $this->em->persist($news);
            $this->em->flush();
        }
    }

    public function onEventEdited(AbstractEventEvent $event)
    {
        $user = $event->getUser();
        $event = $event->getEvent();

        $url = $this->router->generate('event_view', ['event'=>$event->id], Router::ABSOLUTE_URL);


        $news = $this->em->getRepository(News::class)->findOneBy(['referenceType'=>get_class($event),'referenceId'=>$event->id]);
        if(!($news instanceof News) && $event->date instanceof \DateTime){
            $news = new News();
            $news->headline = self::$headline;
            $news->text = sprintf(self::$text, ucfirst($user->getUsername()), $url, $event->name, $event->getFormattedDate('d.m.y'));
            $news->referenceType = get_class($event);
            $news->referenceId = $event->id;
        }
        if($news instanceof News && $event->date instanceof \DateTime)
        {
            $news->text = sprintf(self::$text, ucfirst($user->getUsername()), $url, $event->name, $event->getFormattedDate('d.m.y'));
            $news->group = $event->group;
            $this->em->persist($news);
            $this->em->flush();
        }

    }


    public function onEventDeleted(AbstractEventEvent $event)
    {
        $event = $event->getEvent();
        $news = $this->em->getRepository(News::class)->findOneBy(['referenceType'=>get_class($event),'referenceId'=>$event->id]);
        if($news instanceof News)
        {
            $this->em->remove($news);
            $this->em->flush();
        }

    }
}