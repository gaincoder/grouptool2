<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 22:52
 */

namespace TelegramBundle\Subscriber;


use App\Entity\Poll;
use App\Services\TelegramBot;
use App\Interfaces\TelegramBotInterface;
use Poll\Event\PollAnsweredEvent;
use Poll\Event\PollCommentedEvent;
use Poll\Event\PollCreatedEvent;
use Poll\Event\PollSharedEvent;
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
            PollCreatedEvent::class => 'onPollCreated',
            PollCommentedEvent::class => 'onPollCommented',
            PollSharedEvent::class => 'onPollShared',
            PollAnsweredEvent::class => 'onPollAnswered'
        ];
    }

    public function onPollCreated(PollCreatedEvent $event)
    {
        $user = $event->getUser();
        $poll = $event->getPoll();

        if ($poll->permission == 0) {

            $url = $this->createUrl($poll);
            $message = ":info: <b>Neue Umfrage von " . $user->getUsername() . " hinzugefügt:</b> \n\n";
            $message .= '<a href=\'' . $url . '\'>' . $poll->name . '</a>';
            $this->telegramBot->sendMessage($message);
        }


    }

    public function onPollCommented(PollCommentedEvent $event)
    {

        $commenter = $event->getCommenter();
        $poll = $event->getPoll();
        if ($poll->owner->telegramSupported()) {
            $url = $this->createUrl($poll);
            $message = $commenter->getUsername() . " hat deine Umfrage ";
            $message .= '<a href=\'' . $url . '\'>' . $poll->name . '</a>';
            $message .= ' kommentiert.';
            $this->telegramBot->chatId = $poll->owner->telegramChatId;
            $this->telegramBot->sendMessage($message);
        }

    }

    public function onPollShared(PollSharedEvent $event)
    {
        $user = $event->getUser();
        $poll = $event->getPoll();

        $url = $this->createUrl($poll);
        $message = ":info: <b>" . $user->getUsername() . " möchte auf folgende Umfrage hinweisen:</b> \n\n";
        $message .= '<a href=\'' . $url . '\'>' . $poll->name . '</a>';
        $this->telegramBot->sendMessage($message);

    }

    public function onPollAnswered(PollAnsweredEvent $event)
    {
        $poll = $event->getPoll();
        $user = $event->getUser();


        if ($poll->owner->telegramSupported()) {
            $url = $this->createUrl($poll);
            $message = "Deine Umfrage ";
            $message .= '<a href=\'' . $url . '\'>' . $poll->name . '</a>';
            $message .= ' wurde von ' . $user->getUsername() . ' beantwortet.';
            $this->telegramBot->chatId = $poll->owner->telegramChatId;
            $this->telegramBot->sendMessage($message);
        }
    }


    /**
     * @param Poll $poll
     * @return string
     */
    private function createUrl(Poll $poll): string
    {
        $url = $this->router->generate('poll_view', ['poll' => $poll->id], Router::ABSOLUTE_URL);
        return $url;
    }
}