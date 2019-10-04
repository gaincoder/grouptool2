<?php

namespace EventBundle\Classes;

class EventDateHelper{

    private $today;

    public function __construct()
    {
        $this->today = new \DateTime();
    }



    /**
     * @param \DateTime $today
     */
    public function setToday(\DateTime $today): void
    {
        $this->today = $today;
    }

    /**
     * @param \DateTime $startDate
     * @param \DateInterval $interval
     * @return \DateTime
     */
    public function getActualStartDate(\DateTime $startDate,\DateInterval $interval)
    {
        while($startDate < $this->today){
                $startDate->add($interval);
        }
        return $startDate;
    }

    /**
     * @param \DateTime $startDate
     * @param \DateInterval $interval
     * @return \DateTime[]
     */
    public function getNextDates(\DateTime $startDate,\DateInterval $interval){
        $endDate = $this->today->add(new \DateInterval("P2M"));
        $result = [clone $startDate];
        while($startDate->add($interval) < $endDate){
            $result[] = clone $startDate;
        }
        return $result;
    }

}