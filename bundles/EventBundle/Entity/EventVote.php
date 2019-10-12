<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 16.08.2017
 * Time: 20:07
 */

namespace EventBundle\Entity;

use App\Entity\Poll;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="EventBundle\Repository\EventVote")
 */
class EventVote
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    public $id;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    public $vote;

    /**
     * @var Poll
     * @ORM\ManyToOne(targetEntity="EventBundle\Entity\Event",inversedBy="votes")
     */
    public $event;

    /**
     * @var Poll
     * @ORM\ManyToOne(targetEntity="App\Entity\User",cascade={"remove"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    public $user;

    public function voteToText()
    {
        switch ((int)$this->vote) {
            case 1:
                return "dabei";
            case 2:
                return "nein";
            case 3:
                return "spontan";

        }
    }
}