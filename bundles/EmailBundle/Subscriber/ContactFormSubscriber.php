<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 22:52
 */

namespace EmailBundle\Subscriber;


use App\Event\ContactFormSubmittedEvent;
use App\Event\UserApprovedEvent;
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

class ContactFormSubscriber implements EventSubscriberInterface
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
            ContactFormSubmittedEvent::class => 'onContactFormSubmitted'
        ];
    }

    public function onContactFormSubmitted(ContactFormSubmittedEvent $event)
    {
        $parameters = ['data'=>$event->getData()];
        $this->mailer->sendMessage('email/contact_form.html.twig',$parameters,'andrea.grote@addways.com');

    }

}