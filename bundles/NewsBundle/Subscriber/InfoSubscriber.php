<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 22:52
 */

namespace NewsBundle\Subscriber;


use InfoBundle\Event\InfoDeletedEvent;
use InfoBundle\Event\InfoEditedEvent;
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


    private static $headline = 'Neue Info erstellt';
    private static $text = '%s hat  <a href="%s">%s</a> erstellt';

    public function __construct(EntityManagerInterface $em, RouterInterface $router)
    {
        $this->em = $em;
        $this->router = $router;

    }


    public static function getSubscribedEvents()
    {
        return [
            InfoCreatedEvent::class => 'onInfoCreated',
            InfoEditedEvent::class => 'onInfoEdited',
            InfoDeletedEvent::class => 'onInfoDeleted'
        ];
    }

    public function onInfoCreated(AbstractInfoEvent $event)
    {
        $user = $event->getUser();
        $info = $event->getInfo();

        $url = $this->router->generate('info', [], Router::ABSOLUTE_URL);

        $news = new News();
        $news->headline = self::$headline;
        $news->text = sprintf(self::$text, ucfirst($user->getUsername()), $url, $info->headline);
        $news->referenceType = get_class($info);
        $news->referenceId = $info->id;

        $this->em->persist($news);
        $this->em->flush();

    }

    public function onInfoEdited(AbstractInfoEvent $event)
    {
        $user = $event->getUser();
        $info = $event->getInfo();

        $url = $this->router->generate('info', [], Router::ABSOLUTE_URL);
        $news = $this->em->getRepository(News::class)->findOneBy(['referenceType'=>get_class($info),'referenceId'=>$info->id]);
        if($news instanceof News)
        {
            $news->text = sprintf(self::$text, ucfirst($user->getUsername()), $url, $info->headline);
            $this->em->persist($news);
            $this->em->flush();
        }

    }


    public function onInfoDeleted(AbstractInfoEvent $event)
    {
        $info = $event->getInfo();
        $news = $this->em->getRepository(News::class)->findOneBy(['referenceType'=>get_class($info),'referenceId'=>$info->id]);
        if($news instanceof News)
        {
            $this->em->remove($news);
            $this->em->flush();
        }

    }
    
    
}