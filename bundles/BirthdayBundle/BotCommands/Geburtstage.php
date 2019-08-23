<?php
/**
 * Copyright Tim Moritz. All rights reserved.
 * Creator: tim
 * Date: 08/08/17
 * Time: 16:43
 */


namespace BirthdayBundle\BotCommands;

use App\Interfaces\CommandFireInterface;
use App\Interfaces\CommandHasHelptextInterface;
use App\Services\TelegramBot;
use BirthdayBundle\Entity\Birthday;
use Doctrine\ORM\EntityManagerInterface;
use TelegramBundle\Services\MessageParser;

/**
 * Class Hallo
 * @package App\BotCommands
 */
class Geburtstage implements CommandFireInterface, CommandHasHelptextInterface
{
    /**
     * @var TelegramBot
     */
    private $answerBot;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var MessageParser
     */
    private $messageParser;

    public function __construct(TelegramBot $answerBot, EntityManagerInterface $entityManager, MessageParser $messageParser)
    {
        $this->answerBot = $answerBot;
        $this->entityManager = $entityManager;
        $this->messageParser = $messageParser;
    }

    public function fire()
    {
        $birthdays = $this->entityManager->getRepository(Birthday::class)->findAllOrderedByDay();
        $telegramBot = $this->answerBot;
        $message = ":info::balloon: <b>Geburtstage:</b>\n\n";

        foreach ($birthdays as $birthday) {
            $message .= $birthday->birthdate->format('d.m.y') . " " . $birthday->name . "\n";
        }
        $telegramBot->sendMessage($message);
    }

    public function getHelptext(): string
    {
        return "/geburtstage\nGeburtstagsliste";
    }

    public function checkResponsibility($message): bool
    {
        $this->messageParser->setMessage($message);
        return $this->messageParser->isExactText('/geburtstage');
    }
}