<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 21:52
 */

namespace PollBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * The order.placed event is dispatched each time an order is created
 * in the system.
 */
class PollCommentedEvent extends Event
{

    protected $poll;
    /**
     * @var UserInterface
     */
    private $commenter;

    public function __construct(\PollBundle\Entity\Poll $poll, UserInterface $commenter)
    {
        $this->poll = $poll;
        $this->commenter = $commenter;
    }

    public function getPoll()
    {
        return $this->poll;
    }

    /**
     * @return UserInterface
     */
    public function getCommenter(): UserInterface
    {
        return $this->commenter;
    }


}