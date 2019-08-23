<?php
/**
 * Copyright Tim Moritz. All rights reserved.
 * Creator: tim
 * Date: 08/08/17
 * Time: 16:43
 */


namespace PhotoalbumBundle\BotCommands;

use App\Interfaces\CommandFireInterface;
use App\Interfaces\CommandHasHelptextInterface;
use App\Services\TelegramBot;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use TelegramBundle\Services\MessageParser;

/**
 * Class Hallo
 * @package App\BotCommands
 */
class Fotos implements CommandFireInterface, CommandHasHelptextInterface
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
     * @var RouterInterface
     */
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
        $title = trim(str_replace('/fotos', '', $message->message->text));
        $title = trim(str_replace('/Fotos', '', $title));
        $dir = dirname(__FILE__) . '/../../../var/logs/active_album/';
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $fi = new \FilesystemIterator($dir, \FilesystemIterator::SKIP_DOTS);
        foreach ($fi as $file) {
            $album = $this->entityManager->getRepository('App:Photoalbum')->find(basename($file));
            $this->answerBot->getBot()->sendMessage(
                $this->answerBot->chatId,
                'Es ist bereits das Album "' . $album->name . '" geöffnet! Bitte warten oder /endefotos senden!',
                null,
                true,
                $message->message->message_id
            );

            return;
        }
        if (strlen($title) == 0) {
            $this->answerBot->getBot()->sendMessage($this->answerBot->chatId, 'Bitte hinter /fotos den Titel des Albums angeben', null, true, $message->message->message_id);
            return;
        }
        if ($album = $this->entityManager->getRepository('App:Photoalbum')->findOneBy(['name' => $title])) {
            $answer = 'Fotos die in den nächsten 5 Minuten in die Gruppe geschickt werden, werden dem  Album "' . $title
                . '" hinzugefügt. Vorher kann das hochladen mit /endefotos beendet werden.';

            touch($dir . $album->id);
            $this->answerBot->sendMessage($answer);
        } else {
            $answer = 'Das Album "' . $title . '" wurde nicht gefunden. Soll es neu angelegt werden?';
            $keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
                [
                    [
                        ['text' => 'Ja', 'callback_data' => 'yes'],
                        ['text' => 'Nein', 'callback_data' => 'no']
                    ]
                ],
                true, false, true
            );

            $this->answerBot->getBot()->sendMessage($this->answerBot->chatId, $answer, null, true, $message->message->message_id, $keyboard);
        }

    }

    public function getHelptext(): string
    {
        return  "/fotos -Name des Albums-\nFotos hochladen";
    }

    public function checkResponsibility($message): bool
    {
        $this->messageParser->setMessage($message);
        return $this->messageParser->startsWithText('/fotos');
    }
}