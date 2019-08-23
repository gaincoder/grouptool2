<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 21:52
 */

namespace BirthdayBundle\Event;

use BirthdayBundle\Entity\Birthday;
use Symfony\Contracts\EventDispatcher\Event;


/**
 * The order.placed event is dispatched each time an order is created
 * in the system.
 */
class BirthdayEvent extends Event
{
    public const NAME_TODAY = 'birthday.event.today';
    public const NAME_PREWARNING = 'birthday.event.prewarning';
    /**
     * @var Birthday
     */
    private $birthday;


    public function __construct(Birthday $birthday)
    {

        $this->birthday = $birthday;
    }

    /**
     * @return Birthday
     */
    public function getBirthday(): Birthday
    {
        return $this->birthday;
    }


}