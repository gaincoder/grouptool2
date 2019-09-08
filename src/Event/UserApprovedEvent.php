<?php


namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * The order.placed event is dispatched each time an order is created
 * in the system.
 */
class UserApprovedEvent extends Event
{

    /**
     * @var User
     */
    private $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }


}