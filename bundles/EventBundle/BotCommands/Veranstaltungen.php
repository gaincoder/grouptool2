<?php
/**
 * Copyright Tim Moritz. All rights reserved.
 * Creator: tim
 * Date: 08/08/17
 * Time: 16:43
 */


namespace EventBundle\BotCommands;

use App\Interfaces\CommandFireInterface;
use App\Interfaces\CommandHasHelptextInterface;
use App\Interfaces\CommandInterface;

use App\Services\TelegramBot;
use Doctrine\ORM\EntityManagerInterface;
use EventBundle\Entity\Event;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use TelegramBundle\Services\MessageParser;

/**
 * Class Hallo
 * @package App\BotCommands
 */
class Veranstaltungen implements CommandFireInterface, CommandHasHelptextInterface
{

    private $answerBot;
    private $entityManager;
    private $router;
    /**
     * @var MessageParser
     */
    private $messageParser;

    public function __construct(TelegramBot $answerBot, EntityManagerInterface $entityManager, RouterInterface $router, MessageParser $messageParser)
    {
        $this->answerBot = $answerBot;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->messageParser = $messageParser;
    }

    public function fire()
    {
        $events = $this->entityManager->getRepository(Event::class)->findNextFive();
        $telegramBot = $this->answerBot;
        $message = ":info::calendar: <b>Kommende Veranstaltungen:</b>\n\n";
        $router = $this->router;

        foreach ($events as $event) {
            $url = $router->generate('event_view', ['event' => $event->id], Router::ABSOLUTE_URL);
            $message .= $event->date->format('d.m.') . ' ';
            $icon = $event->public ? ':earth:' : ':lock:';
            $message .= $icon . ' <a href=\'' . $url . '\'>' . $event->name . "</a>\n";
        }
        $telegramBot->sendMessage($message);
    }

    public function getHelptext(): string
    {
        return "/veranstaltungen\nZeigt die nächsten Veranstaltungen";
    }

    public function checkResponsibility($message): bool
    {
        $this->messageParser->setMessage($message);
        return $this->messageParser->isExactText('/veranstaltungen');
    }
}