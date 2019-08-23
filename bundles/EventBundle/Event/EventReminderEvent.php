<?php


namespace EventBundle\Event;


use EventBundle\Entity\Event;

class EventReminderEvent extends \Symfony\Contracts\EventDispatcher\Event
{
    /**
     * @var Event[]
     */
    private $events;

    public function __construct(array $events)
    {
        $this->events = $events;
    }

    /**
     * @return Event[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }


}