<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 22:52
 */

namespace TelegramBundle\Subscriber;


use EventBundle\Command\EventReminderCommand;
use EventBundle\Entity\Event;
use App\Services\TelegramBot;
use App\Interfaces\TelegramBotInterface;
use Event\Event\EventAnsweredEvent;
use Event\Event\EventCommentedEvent;
use Event\Event\EventCreatedEvent;
use Event\Event\EventSharedEvent;
use EventBundle\Event\EventReminderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class EventSubscriber implements EventSubscriberInterface
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
            EventCreatedEvent::class => 'onEventCreated',
            EventCommentedEvent::class => 'onEventCommented',
            EventSharedEvent::class => 'onEventShared',
            EventAnsweredEvent::class => 'onEventAnswered',
            EventReminderEvent::class => 'onEventRemindered'
        ];
    }

    public function onEventCreated(EventCreatedEvent $event)
    {
        $user = $event->getUser();
        $event = $event->getEvent();

        if ($event->permission == 0) {
            $url = $this->createUrl($event);
            $message = sprintf(":info: <b>Neue Aktivität von %s hinzugefügt:</b> \n\n", $user->getUsername());
            $message .= sprintf("<a href=\"%s\">%s</a> am %s \n", $url, $event->name, $event->date->format('d.m.y'));
            $this->telegramBot->sendMessage($message, null, $this->getKeyboard($event));
        }


    }


    public function onEventCommented(EventCommentedEvent $event)
    {
        $commenter = $event->getCommenter();
        $event = $event->getEvent();


        if ($event->owner instanceof UserInterface && $event->owner->telegramSupported()) {

            $url = $this->createUrl($event);
            $message = $commenter->getUsername() . " hat einen Kommmentar zu deiner Aktivität  ";
            $message .= '<a href=\'' . $url . '\'>' . $event->name . '</a>';
            $message .= ' dagelassen.';
            $this->telegramBot->chatId = $event->owner->telegramChatId;
            $this->telegramBot->sendMessage($message);
        }
    }

    public function onEventShared(EventSharedEvent $event)
    {
        $user = $event->getUser();
        $event = $event->getEvent();

        $url = $this->createUrl($event);
        $message = ":info: <b>" . $user->getUsername() . " möchte auf folgende Aktivität hinweisen:</b> \n\n";
        $message .= '<a href=\'' . $url . '\'>' . $event->name . '</a>';
        $this->telegramBot->sendMessage($message, null, $this->getKeyboard($event));

    }

    public function onEventAnswered(EventAnsweredEvent $answeredEvent)
    {
        $event = $answeredEvent->getEvent();
        $answer = $answeredEvent->getAnswer();

        if ($event->owner instanceof UserInterface && $event->owner->telegramSupported()) {

            $url = $this->createUrl($event);
            $message = $answer->user->getUsername() . " hat bei deiner Aktivität ";
            $message .= '<a href=\'' . $url . '\'>' . $event->name . '</a>';
            $message .= ' seine/ihre Teilnahmeinformationen auf "' . $answer->voteToText() . '" geändert.';
            $this->telegramBot->chatId = $event->owner->telegramChatId;
            $this->telegramBot->sendMessage($message);
        }
    }

    public function onEventRemindered(EventReminderEvent $reminderEvent)
    {
        $events = $reminderEvent->getEvents();
        $message = ":info::calendar: <b>Kommende Aktivitäten:</b>\n\n";

        foreach ($events as $event) {
            $url = $this->createUrl($event);
            $message .= $event->date->format('d.m.') . ' ';
            $icon = $event->public ? ':earth:' : ':lock:';
            $message .= $icon . ' <a href=\'' . $url . '\'>' . $event->name . "</a>\n";
        }
        $this->telegramBot->sendMessage($message);
    }

    protected function getKeyboard(Event $event)
    {
        $answerString = 'AnswerEvent;' . $event->id . ';';
        $yes = $answerString . '1';
        $yesBtn = ["text" => 'Dabei', 'callback_data' => $yes];
        $no = $answerString . '2';
        $noBtn = ["text" => 'Nein', 'callback_data' => $no];
        $impulse = $answerString . '3';

        $btns = [[$yesBtn], [$noBtn]];

        if ($event->disableImpulse != true) {
            $impulseBtn = ["text" => 'Spontan', 'callback_data' => $impulse];
            $btns[] = [$impulseBtn];
        }
        return new InlineKeyboardMarkup($btns);

    }


    /**
     * @param EventCreatedEvent $event
     * @return string
     */
    private function createUrl(Event $event): string
    {
        $url = $this->router->generate('event_view', ['event' => $event->id], Router::ABSOLUTE_URL);
        return $url;
    }
}