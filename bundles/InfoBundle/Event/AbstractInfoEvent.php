<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 21:52
 */

namespace InfoBundle\Event;

use App\Entity\User;
use InfoBundle\Entity\Info;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;


/**
 * The order.placed event is dispatched each time an order is created
 * in the system.
 */
abstract class AbstractInfoEvent extends Event
{

    protected $info;
    /**
     * @var User
     */
    private $user;

    public function __construct(Info $info, UserInterface $user)
    {
        $this->info = $info;
        $this->user = $user;
    }

    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }


}