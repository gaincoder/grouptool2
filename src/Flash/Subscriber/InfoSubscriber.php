<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 22:52
 */

namespace App\Flash\Subscriber;


use InfoBundle\Event\InfoCreatedEvent;
use InfoBundle\Event\InfoDeletedEvent;
use InfoBundle\Event\InfoEditedEvent;
use InfoBundle\Event\InfoSharedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class InfoSubscriber implements EventSubscriberInterface
{


    /**
     * @var FlashBagInterface
     */
    private $flashbag;


    public function __construct(FlashBagInterface $flashbag)
    {
        $this->flashbag = $flashbag;

    }

    public static function getSubscribedEvents()
    {
        return [
            InfoCreatedEvent::class => 'onInfoCreated',
            InfoDeletedEvent::class => 'onInfoDeleted',
            InfoEditedEvent::class => 'onInfoEdited',
            InfoSharedEvent::class => 'onInfoShared'
        ];
    }

    public function onInfoCreated(InfoCreatedEvent $event)
    {
        $this->flashbag->add('success', 'Info wurde gespeichert!');
    }

    public function onInfoDeleted(InfoDeletedEvent $event)
    {
        $this->flashbag->add('success', 'Info wurde gelöscht!');
    }

    public function onInfoEdited(InfoEditedEvent $event)
    {
        $this->flashbag->add('success', 'Info wurde gespeichert!');
    }

    public function onInfoShared(InfoSharedEvent $event)
    {
        if ($event->getInfo()->permission == 0) {
            $this->flashbag->add('success', 'Info wurde geteilt!');
        } else {
            $this->flashbag->add('danger', 'Teilen nicht möglich! Sichtbarkeit ist eingeschränkt!');
        }
    }
}