<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 16.08.2017
 * Time: 20:07
 */

namespace PollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class UserVote
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    public $id;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    public $vote;

    /**
     * @var Poll
     * @ORM\ManyToOne(targetEntity="PollBundle\Entity\PollAnswer",inversedBy="votes")
     */
    public $answer;

    /**
     * @var Poll
     * @ORM\ManyToOne(targetEntity="App\Entity\User",cascade={"remove"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    public $user;
}