<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 11.06.2019
 * Time: 20:40
 */

namespace PollBundle\Event;


use PollBundle\Entity\Poll;
use App\Entity\UserVote;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;


class PollAnsweredEvent extends Event
{

    protected $poll;

    private $user;

    public function __construct(Poll $poll, UserInterface $user)
    {
        $this->poll = $poll;
        $this->user = $user;
    }


    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Poll
     */
    public function getPoll(): Poll
    {
        return $this->poll;
    }
}