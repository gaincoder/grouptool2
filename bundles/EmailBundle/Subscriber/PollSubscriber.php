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

class PollSubscriber implements EventSubscriberInterface
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
            PollCreatedEvent::class => 'onPollCreated'
        ];
    }

    public function onPollCreated(PollCreatedEvent $event)
    {

        $poll = $event->getPoll();
        $recievers = $this->receiverCollector->getReceivers($poll,Mails::MAIL_POLL_NEW);
        $parameters = [
            'poll' => $poll,
            'link' => $this->router->generate('poll_view',['poll'=>$poll->id],Router::ABSOLUTE_URL)
        ];

        foreach ($recievers as $reciever) {
            $mail = $reciever->getEmail();
            $parameters['receiver'] = $reciever;
            $this->mailer->sendMessage('email/new_poll.html.twig',$parameters,$mail);
        }
    }

}