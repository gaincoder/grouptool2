<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 21:52
 */

namespace CompanyBundle\Event;

use App\Entity\User;
use CompanyBundle\Entity\Company;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;


/**
 * The order.placed event is dispatched each time an order is created
 * in the system.
 */
abstract class AbstractCompanyEvent extends Event
{

    protected $company;
    /**
     * @var User
     */
    private $user;

    public function __construct(Company $company, UserInterface $user)
    {
        $this->company = $company;
        $this->user = $user;
    }

    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }


}