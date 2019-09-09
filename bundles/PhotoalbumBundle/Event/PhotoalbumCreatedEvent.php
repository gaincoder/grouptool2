<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 10.06.2019
 * Time: 21:52
 */

namespace PhotoalbumBundle\Event;

use App\Entity\User;
use PhotoalbumBundle\Entity\Photoalbum;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;


class PhotoalbumCreatedEvent extends Event implements PhotoalbumEventInterface
{

    protected $photoalbum;
    /**
     * @var User
     */
    private $user;

    public function __construct(Photoalbum $photoalbum, UserInterface $user)
    {
        $this->photoalbum = $photoalbum;
        $this->user = $user;
    }

    public function getPhotoalbum()
    {
        return $this->photoalbum;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }


}