<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 22:52
 */

namespace EmailBundle\Subscriber;


use EmailBundle\Enums\Mails;
use EmailBundle\Services\ReceiverCollector;
use EmailBundle\Services\TwigMailerInterface;
use EventBundle\Event\EventCreatedEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Mailer\TwigSwiftMailer;
use InfoBundle\Entity\Info;
use App\Services\EmailBot;
use App\Interfaces\EmailBotInterface;
use InfoBundle\Event\AbstractInfoEvent;
use InfoBundle\Event\InfoCreatedEvent;
use InfoBundle\Event\InfoSharedEvent;
use PollBundle\Event\PollCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class EventSubscriber implements EventSubscriberInterface
{

    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var TwigMailerInterface
     */
    private $mailer;
    /**
     * @var ReceiverCollector
     */
    private $receiverCollector;


    public function __construct(RouterInterface $router, TwigMailerInterface $mailer, ReceiverCollector $receiverCollector )
    {
        $this->router = $router;
        $this->mailer = $mailer;
        $this->receiverCollector = $receiverCollector;
    }

    public static function getSubscribedEvents()
    {
        return [
            EventCreatedEvent::class => 'onEventCreated'
        ];
    }

    public function onEventCreated(EventCreatedEvent $event)
    {

//        $event = $event->getEvent();
//        $recievers = $this->receiverCollector->getReceivers($event,Mails::MAIL_EVENT_NEW);
//        $parameters = [
//            'event' => $event,
//            'link' => $this->router->generate('event_view',['event'=>$event->id],Router::ABSOLUTE_URL)
//        ];
//
//        foreach ($recievers as $reciever) {
//            $mail = $reciever->getEmail();
//            $parameters['receiver'] = $reciever;
//            $this->mailer->sendMessage('email/new_event.html.twig',$parameters,$mail);
//        }
    }

}