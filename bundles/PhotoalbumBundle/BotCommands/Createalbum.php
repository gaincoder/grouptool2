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
class Createalbum implements CommandFireInterface
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
        $title = trim(str_replace('/createalbum', '', $message->message->text));

        if (!$album = $this->entityManager->getRepository(Photoalbum::class)->findOneBy(['name' => $title])) {
            $album = new Photoalbum();
            $album->name = $title;
            $this->entityManager->persist($album);
            $this->entityManager->flush();
            $answer = 'Das Album "' . $title . '" wurde angelegt.';
            $this->answerBot->sendMessage($answer);
        }
    }

    public function checkResponsibility($message): bool
    {
        $this->messageParser->setMessage($message);
        return $this->messageParser->startsWithText('/createAlbum');
    }
}