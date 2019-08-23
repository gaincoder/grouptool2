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
use PhotoalbumBundle\Event\PhotoalbumEvent;
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


    public function __construct(EntityManagerInterface $em, RouterInterface $router)
    {
        $this->em = $em;
        $this->router = $router;

    }

    public static function getSubscribedEvents()
    {
        return [
            PhotoalbumEvent::NAME_CREATED => 'onPhotoalbumCreated'
        ];
    }

    public function onPhotoalbumCreated(PhotoalbumEvent $event)
    {
        $user = $event->getUser();
        $photoalbum = $event->getPhotoalbum();

        $url = $this->router->generate('photoalbum', [], Router::ABSOLUTE_URL);

        $news = new News('Neues Fotoalbum: ', ucfirst($user->getUsername()) . ' hat <a href="'
            . $url . '">' . $photoalbum->name . '</a> erstellt', $photoalbum->permission);

        $this->em->persist($news);
        $this->em->flush();

    }
}