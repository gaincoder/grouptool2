<?php


namespace EventBundle\Services;


use EventBundle\Classes\EventDateHelper;
use EventBundle\Entity\Event;
use EventBundle\Entity\RepeatingEvent;
use EventBundle\Enums\RepeatingType;

class RepeatingEvents
{
    public function updateEvents(RepeatingEvent $repeatingEvent){
        $helper = new EventDateHelper();
        $startDate = $helper->getActualStartDate($repeatingEvent->start,$repeatingEvent->getInterval());
        $nextDates = $helper->getNextDates($startDate,$repeatingEvent->getInterval());
        foreach ($nextDates as $index => $date){
            $this->updateEvent($repeatingEvent,$index,$date);
        }
    }

    private function updateEvent(RepeatingEvent $repeatingEvent, int $index, \DateTime $date)
    {
        /** @var Event[] $futureEvents */
        $futureEvents = $repeatingEvent->getFutureEvents();
        if(isset($futureEvents[$index]) && $futureEvents[$index] instanceof Event){
            if(!$futureEvents[$index]->manualChanged){
                $this->setEventData($futureEvents[$index],$repeatingEvent,$date);
            }
        }else{
            $event = new Event();
            $event->repeatingEvent = $repeatingEvent;
            $this->setEventData($event,$repeatingEvent,$date);
        }
    }

    private function setEventData(Event $index, RepeatingEvent $repeatingEvent, \DateTime $date)
    {
    }




}