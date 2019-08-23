<?php
/**
 * Copyright Tim Moritz. All rights reserved.
 * Creator: tim
 * Date: 08/08/17
 * Time: 16:43
 */


namespace TelegramBundle\BotCommands;

use App\Interfaces\CommandFireInterface;
use App\Services\TelegramBot;
use TelegramBundle\Services\MessageParser;

/**
 * Class Hallo
 * @package App\BotCommands
 */
class Moin implements CommandFireInterface
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
        $answer = "Ahoi :wink:";
        $this->answerBot->sendMessage($answer);
    }

    public function checkResponsibility($message): bool
    {
        $this->messageParser->setMessage($message);
        return $this->messageParser->isExactText('moin');
    }
}