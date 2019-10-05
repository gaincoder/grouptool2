<?php


namespace EventBundle\Services;


use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use EventBundle\Classes\EventDateHelper;
use EventBundle\Entity\Event;
use EventBundle\Entity\RepeatingEvent;
use EventBundle\Enums\RepeatingType;

class RepeatingEvents
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    private $isCronjon = false;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param bool $isCronjon
     */
    public function setIsCronjon(bool $isCronjon): void
    {
        $this->isCronjon = $isCronjon;
    }


    public function updateEvents(RepeatingEvent $repeatingEvent){
        $helper = new EventDateHelper();
        $startDate = $helper->getActualStartDate($repeatingEvent->start,$repeatingEvent->getInterval());
        $nextDates = $helper->getNextDates($startDate,$repeatingEvent->getInterval());
        $futureEvents = $repeatingEvent->getFutureEvents(new \DateTime());
        foreach ($nextDates as $index => $date){
            $this->updateEvent($repeatingEvent,$index,$date,$futureEvents);
        }
        foreach($futureEvents as $eIndex => $event){
            if($eIndex > $index && !$event->manualChanged){
                $this->entityManager->remove($event);
            }
        }
        $this->entityManager->flush();
    }

    private function updateEvent(RepeatingEvent $repeatingEvent, int $index, \DateTime $date, array $futureEvents)
    {

        if(isset($futureEvents[$index]) && $futureEvents[$index] instanceof Event){
            if(!$futureEvents[$index]->archived){
                $this->setEventData($futureEvents[$index],$repeatingEvent,$date);
            }
        }else{
            $event = new Event();
            $event->repeatingEvent = $repeatingEvent;
            $this->setEventData($event,$repeatingEvent,$date);
        }
    }

    private function setEventData(Event $event, RepeatingEvent $repeatingEvent, \DateTime $date)
    {
        if(!$event->manualChanged){
            $event->date = $date;
            $event->location = $repeatingEvent->location;
        }

        $event->public = $repeatingEvent->public;
        $event->name = $repeatingEvent->name;
        $event->info = $repeatingEvent->info;
        $event->maxPersons = $repeatingEvent->maxPersons;
        $event->disableAnswer = $repeatingEvent->disableAnswer;
        $event->disableImpulse = $repeatingEvent->disableImpulse;
        $event->group = $repeatingEvent->group;
        $event->createdBy = $repeatingEvent->createdBy;
        $event->notifications = $repeatingEvent->notifications;
        if(!$this->isCronjon){
            $event->updatedBy = $repeatingEvent->updatedBy;
        }
        $this->entityManager->persist($event);
    }



}