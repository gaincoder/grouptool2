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
use PollBundle\Event\AbstractPollEvent;
use PollBundle\Event\PollCreatedEvent;
use PollBundle\Event\PollDeletedEvent;
use PollBundle\Event\PollEditedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class PollSubscriber implements EventSubscriberInterface
{


    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var RouterInterface
     */
    private $router;
    private static $headline = 'Neue Umfrage erstellt';
    private static $text = '<a href="%s">%s</a> wurde von %s erstellt';


    public function __construct(EntityManagerInterface $em, RouterInterface $router)
    {
        $this->em = $em;
        $this->router = $router;

    }

    public static function getSubscribedEvents()
    {
        return [
            PollCreatedEvent::class => 'onPollCreated',
            PollEditedEvent::class => 'onPollEdited',
            PollDeletedEvent::class => 'onPollDeleted'
        ];
    }




    public function onPollCreated(AbstractPollEvent $event)
    {
        $user = $event->getUser();
        $poll = $event->getPoll();

        $url = $this->router->generate('poll_view', ['poll'=>$poll->id], Router::ABSOLUTE_URL);

        $news = new News();
        $news->headline = self::$headline;
        $news->text = sprintf(self::$text,  $url, $poll->name, ucfirst($user->getUsername()));
        $news->referenceType = get_class($poll);
        $news->referenceId = $poll->id;
        $news->group = $poll->group;
        $this->em->persist($news);
        $this->em->flush();

    }

    public function onPollEdited(AbstractPollEvent $event)
    {
        $user = $event->getUser();
        $poll = $event->getPoll();

        $url = $this->router->generate('poll_view', ['poll'=>$poll->id], Router::ABSOLUTE_URL);


        $news = $this->em->getRepository(News::class)->findOneBy(['referenceType'=>get_class($poll),'referenceId'=>$poll->id]);
        if($news instanceof News)
        {
            $news->text = sprintf(self::$text,  $url, $poll->name, ucfirst($user->getUsername()));
            $news->group = $poll->group;
            $this->em->persist($news);
            $this->em->flush();
        }

    }


    public function onPollDeleted(AbstractPollEvent $event)
    {
        $poll = $event->getPoll();
        $news = $this->em->getRepository(News::class)->findOneBy(['referenceType'=>get_class($poll),'referenceId'=>$poll->id]);
        if($news instanceof News)
        {
            $this->em->remove($news);
            $this->em->flush();
        }

    }

}