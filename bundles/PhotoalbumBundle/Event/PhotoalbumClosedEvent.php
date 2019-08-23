<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 21:52
 */

namespace PhotoalbumBundle\Event;

use PhotoalbumBundle\Entity\Photoalbum;
use Symfony\Contracts\EventDispatcher\Event;


/**
 * The order.placed event is dispatched each time an order is created
 * in the system.
 */
class PhotoalbumClosedEvent extends Event
{
    protected $photoalbum;

    public function __construct(Photoalbum $photoalbum)
    {
        $this->photoalbum = $photoalbum;
    }

    public function getPhotoalbum()
    {
        return $this->photoalbum;
    }




}