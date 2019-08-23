<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 21:52
 */

namespace PollBundle\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * The order.placed event is dispatched each time an order is created
 * in the system.
 */
class PollSharedEvent extends Event
{

    protected $poll;
    /**
     * @var User
     */
    private $user;

    public function __construct(\PollBundle\Entity\Poll $poll, UserInterface $user)
    {
        $this->poll = $poll;
        $this->user = $user;
    }

    public function getPoll()
    {
        return $this->poll;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }


}