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



abstract class AbstractPollEvent extends Event
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

    /**
     * @return \PollBundle\Entity\Poll
     */
    public function getPoll()
    {
        return $this->poll;
    }

    /**
     * @return User
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }


}