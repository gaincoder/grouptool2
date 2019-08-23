<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 22:52
 */

namespace TelegramBundle\Subscriber;


use InfoBundle\Entity\Info;
use App\Services\TelegramBot;
use App\Interfaces\TelegramBotInterface;
use InfoBundle\Event\AbstractInfoEvent;
use InfoBundle\Event\InfoCreatedEvent;
use InfoBundle\Event\InfoSharedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class InfoSubscriber implements EventSubscriberInterface
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
            InfoCreatedEvent::class => 'onInfoCreated',
            InfoSharedEvent::class => 'onInfoShared',
        ];
    }

    public function onInfoCreated(AbstractInfoEvent $event)
    {
        $user = $event->getUser();
        $info = $event->getInfo();

        if ($info->permission == 0) {

            $url = $this->createUrl($info);
            $message = ":info: <b>Neue Info von " . $user->getUsername() . " hinzugefügt:</b> \n\n";
            $message .= '<a href=\'' . $url . '#' . $info->id . '\'>' . $info->headline . "</a> \n";
            $this->telegramBot->sendMessage($message);
        }


    }


    public function onInfoShared(AbstractInfoEvent $event)
    {
        $user = $event->getUser();
        $info = $event->getInfo();

        $url = $this->createUrl($info);
        $message = ":info: <b>" . $user->getUsername() . " möchte auf folgende Info hinweisen:</b> \n\n";
        $message .= "<b>" . $info->headline . "</b>\n\n";
        $message .= $info->text . "\n\n";
        $message .= '<a href=\'' . $url . '\'>Infos anzeigen</a>';
        $this->telegramBot->sendMessage($message);

    }


    /**
     * @param \InfoBundle\Entity\Info $info
     * @return string
     */
    private function createUrl(Info $info): string
    {
        $url = $this->router->generate('info', [], Router::ABSOLUTE_URL);
        return $url;
    }
}