<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 22:52
 */

namespace TelegramBundle\Subscriber;


use App\Entity\Photoalbum;
use App\Services\TelegramBot;
use App\Interfaces\TelegramBotInterface;

use PhotoalbumBundle\Event\PhotoalbumClosedEvent;
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
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TelegramBot
     */
    private $telegramBot;

    public function __construct(RouterInterface $router, TelegramBotInterface $telegramBot)
    {
        $this->router = $router;
        $this->telegramBot = $telegramBot;
    }

    public static function getSubscribedEvents()
    {
        return [
            PhotoalbumCreatedEvent::class => 'onPhotoalbumCreated',
            PhotoalbumClosedEvent::class => 'onPhotoAlbumClosed'
        ];
    }


    public function onPhotoalbumCreated(PhotoalbumEventInterface $event)
    {
        $user = $event->getUser();
        $photoalbum = $event->getPhotoalbum();

        if ($photoalbum->permission == 0) {

            $url = $this->createUrl($photoalbum);
            $message = ":info: <b>Neues Fotoalbum von " . $user->getUsername() . " hinzugefügt:</b> \n\n";
            $message .= '<a href=\'' . $url . '\'>' . $photoalbum->name . "</a>\n";
            $this->telegramBot->sendMessage($message);
        }


    }

    public function onPhotoAlbumClosed(PhotoalbumClosedEvent $event)
    {
        $album = $event->getPhotoalbum();
        $this->telegramBot->sendMessage('Die 5 Minuten sind um! Das Album "' . $album->name . '" wurde geschlossen! Wenn weitere Fotos hinzugefügt werden sollen, bitte erneut mit "/fotos ' . $album->name . '" öffnen.');
    }

    /**
     * @param Photoalbum $photoalbum
     * @return string
     */
    private function createUrl(Photoalbum $photoalbum): string
    {
        $url = $this->router->generate('photoalbum', [], Router::ABSOLUTE_URL);
        return $url;
    }
}