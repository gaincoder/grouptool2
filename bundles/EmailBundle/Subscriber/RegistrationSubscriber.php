<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 22:52
 */

namespace EmailBundle\Subscriber;


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
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class RegistrationSubscriber implements EventSubscriberInterface
{

    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var TwigMailerInterface
     */
    private $mailer;


    public function __construct(RouterInterface $router, TwigMailerInterface $mailer )
    {
        $this->router = $router;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_CONFIRM => 'onRegistrationConfirmed'
        ];
    }

    public function onRegistrationConfirmed(GetResponseUserEvent $event)
    {
        $recievers = $event->getUser()->company->registrationsTo;

        $parameters = [
            'newUser' => $event->getUser(),
            'approveLink' => $this->router->generate('user_edit',['user'=>$event->getUser()->getId()],Router::ABSOLUTE_URL)
        ];

        foreach ($recievers as $reciever) {
            $mail = $reciever->getEmail();
            $parameters['receiver'] = $reciever;
            $this->mailer->sendMessage('email/new_registration.html.twig',$parameters,$mail);
        }
    }

}