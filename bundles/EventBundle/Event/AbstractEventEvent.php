<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 29.06.2019
 * Time: 22:30
 */

namespace EventBundle\Event;


use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

abstract class AbstractEventEvent extends Event
{
    protected $event;
    /**
     * @var User
     */
    private $user;

    public function __construct(\EventBundle\Entity\Event $event, UserInterface $user)
    {
        $this->event = $event;
        $this->user = $user;
    }

    /**
     * @return \EventBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return User
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }
}