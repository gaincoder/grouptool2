<?php

namespace EventBundle\Tests;

use EventBundle\Classes\EventDateHelper;
use PHPUnit\Framework\TestCase;

class EventDateHelperTest extends TestCase
{

    private $repeatingEvents;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repeatingEvents = new EventDateHelper();
        $this->repeatingEvents->setToday(new \DateTime('2019-10-04 08:00:00'));
    }

    public function testGetActualStartDateWithFutureDate()
    {
        $savedStartDate = new \DateTime("2019-10-09");
        $interval = new \DateInterval("P1D");
        $result = $this->repeatingEvents->getActualStartDate($savedStartDate,$interval);
        $this->assertEquals($savedStartDate,$result);
    }

    public function testGetActualStartDateWithFutureDatePlus7Days()
    {
        $savedStartDate = new \DateTime("2019-10-09");
        $interval = new \DateInterval("P7D");
        $result = $this->repeatingEvents->getActualStartDate($savedStartDate,$interval);
        $this->assertEquals($savedStartDate,$result);
    }

    public function testGetActualStartDateWithYesterday()
    {
        $savedStartDate = new \DateTime("2019-10-03 07:00:00");
        $interval = new \DateInterval("P1D");
        $result = $this->repeatingEvents->getActualStartDate($savedStartDate,$interval);
        $this->assertEquals(new \DateTime("2019-10-05 07:00:00"),$result);
    }

    public function testGetActualStartDateWithYesterdayPlus7Days()
    {
        $savedStartDate = new \DateTime("2019-10-03 07:00:00");
        $interval = new \DateInterval("P7D");
        $result = $this->repeatingEvents->getActualStartDate($savedStartDate,$interval);
        $this->assertEquals(new \DateTime("2019-10-10 07:00:00"),$result);
    }


    public function testGetActualStartDateWithPastDate()
    {
        $savedStartDate = new \DateTime("2017-01-03 07:00:00");
        $interval = new \DateInterval("P1D");
        $result = $this->repeatingEvents->getActualStartDate($savedStartDate,$interval);
        $this->assertEquals(new \DateTime("2019-10-05 07:00:00"),$result);
    }

    public function testGetActualStartDateWithPastDatePlus7Days()
    {
        $savedStartDate = new \DateTime("2010-05-04 07:00:00");
        $interval = new \DateInterval("P7D");
        $result = $this->repeatingEvents->getActualStartDate($savedStartDate,$interval);
        $this->assertEquals(new \DateTime("2019-10-08 07:00:00"),$result);
    }

    public function testGetActualStartDateWithPastDatePlus1Month()
    {
        $savedStartDate = new \DateTime("2010-05-04 07:00:00");
        $interval = new \DateInterval("P1M");
        $result = $this->repeatingEvents->getActualStartDate($savedStartDate,$interval);
        $this->assertEquals(new \DateTime("2019-11-04 07:00:00"),$result);
    }

    public function testGetNextDatesForDaily()
    {
        $start = new \DateTime("2019-12-01 04:00:00");
        $interval = new \DateInterval("P1D");
        $result = $this->repeatingEvents->getNextDates($start,$interval);
        $this->assertCount(4,$result);
    }

    public function testGetNextDatesForWeeks()
    {
        $start = new \DateTime("2019-08-01 04:00:00");
        $interval = new \DateInterval("P7D");
        $result = $this->repeatingEvents->getNextDates($start,$interval);
        $this->assertCount(18,$result);
    }

    public function testGetNextDatesForMonth()
    {
        $start = new \DateTime("2019-08-01 04:00:00");
        $interval = new \DateInterval("P1M");
        $result = $this->repeatingEvents->getNextDates($start,$interval);
        $this->assertCount(5,$result);
    }
}
