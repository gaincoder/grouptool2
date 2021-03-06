<?php
/**
 * Copyright Tim Moritz. All rights reserved.
 * Creator: tim
 * Date: 24/08/17
 * Time: 11:12
 */


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Class Comment
 * @package App\Entity
 * @ORM\Entity()
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    public $id;

    /**
     * @var Poll
     * @ORM\ManyToOne(targetEntity="App\Entity\User",cascade={"remove"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    public $user;

    /**
     * @ORM\Column(type="text")
     */
    public $text;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    public $created;

    public function __construct()
    {
        $this->created = new \DateTime();
    }
}