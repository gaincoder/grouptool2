<?php

namespace EventBundle\Event;


use EventBundle\Entity\EventVote;
use Symfony\Contracts\EventDispatcher\Event;


class EventAnsweredEvent extends Event
{

    protected $event;

    private $answer;

    public function __construct(\EventBundle\Entity\Event $event, EventVote $answer)
    {
        $this->event = $event;
        $this->answer = $answer;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function getAnswer()
    {
        return $this->answer;
    }
}