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
use PhotoalbumBundle\Event\PhotoalbumCreatedEvent;
use PhotoalbumBundle\Event\PhotoalbumDeletedEvent;
use PhotoalbumBundle\Event\PhotoalbumEditedEvent;
use PhotoalbumBundle\Event\PhotoalbumEvent;
use PhotoalbumBundle\Event\PhotoalbumEventInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class PhotoalbumSubscriber implements EventSubscriberInterface
{


    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var RouterInterface
     */
    private $router;

    private static $headline = 'Neues Fotoalum';
    private static $text = '%s hat  <a href="%s">%s</a> erstellt';

    public function __construct(EntityManagerInterface $em, RouterInterface $router)
    {
        $this->em = $em;
        $this->router = $router;

    }

    public static function getSubscribedEvents()
    {
        return [
            PhotoalbumCreatedEvent::class => 'onPhotoalbumCreated',
            PhotoalbumEditedEvent::class => 'onPhotoalbumEdited',
            PhotoalbumDeletedEvent::class => 'onPhotoalbumDeleted'
        ];
    }

    public function onPhotoalbumCreated(PhotoalbumEventInterface $event)
    {
        $user = $event->getUser();
        $photoalbum = $event->getPhotoalbum();

        $url = $this->router->generate('photoalbum_view', ['photoalbum'=>$photoalbum->id], Router::ABSOLUTE_URL);

        $news = new News();
        $news->headline = self::$headline;
        $news->text = sprintf(self::$text, ucfirst($user->getUsername()), $url, $photoalbum->name);
        $news->referenceType = get_class($photoalbum);
        $news->referenceId = $photoalbum->id;
        $this->em->persist($news);
        $this->em->flush();

    }


    public function onPhotoalbumEdited(PhotoalbumEventInterface $event)
    {
        $user = $event->getUser();
        $photoalbum = $event->getPhotoalbum();

        $url = $this->router->generate('photoalbum_view', ['photoalbum'=>$photoalbum->id], Router::ABSOLUTE_URL);
        $news = $this->em->getRepository(News::class)->findOneBy(['referenceType'=>get_class($photoalbum),'referenceId'=>$photoalbum->id]);
        if($news instanceof News)
        {
            $news->text = sprintf(self::$text, ucfirst($user->getUsername()), $url, $photoalbum->name);
            $this->em->persist($news);
            $this->em->flush();
        }

    }


    public function onPhotoalbumDeleted(PhotoalbumEventInterface $event)
    {
        $photoalbum = $event->getPhotoalbum();
        $news = $this->em->getRepository(News::class)->findOneBy(['referenceType'=>get_class($photoalbum),'referenceId'=>$photoalbum->id]);
        if($news instanceof News)
        {
            $this->em->remove($news);
            $this->em->flush();
        }

    }
}