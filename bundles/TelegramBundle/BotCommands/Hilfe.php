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
use App\Services\TelegramBot;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use TelegramBundle\Services\MessageParser;
use TelegramBundle\Services\TelegramBotCommand;

/**
 * Class Hallo
 * @package App\BotCommands
 */
class Hilfe implements CommandFireInterface, CommandHasHelptextInterface
{


    /**
     * @var TelegramBot
     */
    private $answerBot;

    /**
     * @var TelegramBotCommand
     */
    private $commander;
    /**
     * @var MessageParser
     */
    private $messageParser;

    public function __construct(TelegramBot $answerBot, TelegramBotCommand $commander, MessageParser $messageParser)
    {
        $this->answerBot = $answerBot;
        $this->commander = $commander;
        $this->messageParser = $messageParser;
    }

    public function fire()
    {
        $answer = "Folgende Befehle kenne ich:\n\n";
        foreach ($this->commander->getCommands() as $command){
            if($command instanceof CommandHasHelptextInterface){
                $answer .= $command->getHelpText()."\n\n";
            }
        }
        $this->answerBot->sendMessage($answer);
    }

    public function getHelptext(): string
    {
       return "/hilfe\nZeigt diese Liste an";
    }

    public function checkResponsibility($message): bool
    {
        $this->messageParser->setMessage($message);
        return $this->messageParser->isExactText('/hilfe');
    }
}