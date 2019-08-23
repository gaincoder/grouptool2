<?php
/**
 * Copyright Tim Moritz. All rights reserved.
 * Creator: tim
 * Date: 08/08/17
 * Time: 16:43
 */


namespace PhotoalbumBundle\BotCommands;

use App\Interfaces\CommandFireInterface;
use App\Services\TelegramBot;
use Doctrine\ORM\EntityManagerInterface;
use PhotoalbumBundle\Entity\Photoalbum;
use Symfony\Component\Routing\RouterInterface;
use TelegramBundle\Services\MessageParser;

/**
 * Class Hallo
 * @package App\BotCommands
 */
class Endefotos implements CommandFireInterface
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
        $dir = dirname(__FILE__) . '/../../../var/logs/active_album/';
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $fi = new \FilesystemIterator($dir, \FilesystemIterator::SKIP_DOTS);
        foreach ($fi as $file) {
            $album = $this->entityManager->getRepository(Photoalbum::class)->find(basename($file));
            unlink($file);
            $this->answerBot->sendMessage('Das Album "' . $album->name . '" wurde geschlossen! Wenn weitere Fotos hinzugefügt werden sollen, bitte erneut mit "/fotos ' . $album->name . '" öffnen.');

        }
    }

    public function checkResponsibility($message): bool
    {
        $this->messageParser->setMessage($message);
        return $this->messageParser->isExactText('/endefotos');
    }
}