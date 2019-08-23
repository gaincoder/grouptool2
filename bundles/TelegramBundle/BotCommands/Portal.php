<?php
/**
 * Copyright Tim Moritz. All rights reserved.
 * Creator: tim
 * Date: 08/08/17
 * Time: 16:43
 */


namespace TelegramBundle\BotCommands;

use App\Interfaces\CommandFireInterface;
use App\Interfaces\CommandHasHelptextInterface;
use App\Interfaces\CommandInterface;
use App\Services\TelegramBot;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use TelegramBundle\Services\MessageParser;

/**
 * Class Hallo
 * @package App\BotCommands
 */
class Portal implements CommandFireInterface, CommandHasHelptextInterface
{

    /**
     * @var TelegramBot
     */
    private $answerBot;
    /**
     * @var MessageParser
     */
    private $messageParser;


    public function __construct(TelegramBot $answerBot, MessageParser $messageParser)
    {
        $this->answerBot = $answerBot;
        $this->messageParser = $messageParser;
    }

    public function fire()
    {
        $answer = "https://lippe.grouptool.de";
        $this->answerBot->sendMessage($answer);
    }

    public function getHelptext(): string
    {
        return "/portal\nLink zum Gruppenportal";
    }

    public function checkResponsibility($message): bool
    {
        $this->messageParser->setMessage($message);
        return $this->messageParser->isExactText('/portal');
    }
}