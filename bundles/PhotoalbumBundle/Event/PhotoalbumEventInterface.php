<?php

namespace PhotoalbumBundle\Event;


use App\Entity\User;
use PhotoalbumBundle\Entity\Photoalbum;

/**
 * The order.placed event is dispatched each time an order is created
 * in the system.
 */
interface PhotoalbumEventInterface
{
    /**
     * @return Photoalbum
     */
    public function getPhotoalbum();

    /**
     * @return User
     */
    public function getUser(): User;
}