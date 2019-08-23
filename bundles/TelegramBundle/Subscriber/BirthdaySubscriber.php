<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 22:52
 */

namespace TelegramBundle\Subscriber;


use BirthdayBundle\Event\BirthdayEvent;
use App\Services\TelegramBot;
use App\Interfaces\TelegramBotInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class BirthdaySubscriber implements EventSubscriberInterface
{


    /**
     * @var TelegramBot
     */
    private $telegramBot;

    public function __construct(TelegramBotInterface $telegramBot)
    {
        $this->telegramBot = $telegramBot;
    }

    public static function getSubscribedEvents()
    {
        return [
            BirthdayEvent::NAME_TODAY => 'onBirthdayToday',
            BirthdayEvent::NAME_PREWARNING => 'onBirthdayPrewarning'
        ];
    }


    public function onBirthdayToday(BirthdayEvent $event)
    {
        $birthday = $event->getBirthday();
        $message = ":balloon::balloon::balloon::balloon::balloon:\n\n";
        $message .= '<b>' . $birthday->name . '</b> wird heute ' . $birthday->getAgeThisYear() . " Jahre alt!\n";
        $message .= "Herzlichen Glückwunsch!\n\n";
        $message .= ":balloon::balloon::balloon::balloon::balloon:";
        $this->telegramBot->sendMessage($message);
    }


    public function onBirthdayPrewarning(BirthdayEvent $event)
    {
        $birthday = $event->getBirthday();
        $message = ":info::calendar: <b>Ankündigung Geburtstag:</b>\n\n";
        $message .= 'Am ' . $birthday->birthdate->format('d.m.') . ' wird ' . $birthday->name . ' ' . $birthday->getNextAge() . " Jahre alt.\n";

        $this->telegramBot->sendMessage($message);

    }

}