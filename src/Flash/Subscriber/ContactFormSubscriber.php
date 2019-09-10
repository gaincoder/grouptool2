<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 22:52
 */

namespace App\Flash\Subscriber;


use App\Event\ContactFormSubmittedEvent;
use InfoBundle\Event\InfoCreatedEvent;
use InfoBundle\Event\InfoDeletedEvent;
use InfoBundle\Event\InfoEditedEvent;
use InfoBundle\Event\InfoSharedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ContactFormSubscriber implements EventSubscriberInterface
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
            ContactFormSubmittedEvent::class => 'onContactFormSubmitted',
        ];
    }

    public function onContactFormSubmitted(ContactFormSubmittedEvent $event)
    {
        $this->flashbag->add('success', 'Ihre Kontaktanfrage wurde gesendet!');
    }

}