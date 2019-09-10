<?php


namespace App\Event;

use App\Entity\ContactForm;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * The order.placed event is dispatched each time an order is created
 * in the system.
 */
class ContactFormSubmittedEvent extends Event
{


    /**
     * @var ContactForm
     */
    private $data;

    public function __construct(ContactForm $data)
    {
        $this->data = $data;
    }

    /**
     * @return ContactForm
     */
    public function getData(): ContactForm
    {
        return $this->data;
    }





}