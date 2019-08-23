<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 22:52
 */

namespace App\Flash\Subscriber;


use PhotoalbumBundle\Event\PhotoalbumEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class PhotoalbumSubscriber implements EventSubscriberInterface
{


    /**
     * @var FlashBagInterface
     */
    private $flashbag;


    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashbag = $flashBag;

    }

    public static function getSubscribedEvents()
    {
        return [
            PhotoalbumEvent::NAME_CREATED => 'onPhotoalbumCreated',
            PhotoalbumEvent::NAME_DELETED => 'onPhotoalbumDeleted',
            PhotoalbumEvent::NAME_EDITED => 'onPhotoalbumEdited'
        ];
    }

    public function onPhotoalbumCreated(PhotoalbumEvent $event)
    {
        $this->flashbag->add('success', 'Fotoalbum wurde gespeichert!');
    }

    public function onPhotoalbumDeleted()
    {
        $this->flashbag->add('success', 'Fotoalbum wurde gelöscht!');
    }

    public function onPhotoalbumEdited()
    {
        $this->flashbag->add('success', 'Fotoalbum wurde gespeichert!');
    }
}