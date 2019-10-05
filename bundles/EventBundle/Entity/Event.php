<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 31.07.2017
 * Time: 19:48
 */

namespace EventBundle\Entity;

use App\Entity\Comment;
use App\Entity\Group;
use App\Entity\User;
use App\Interfaces\GroupVisbilityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity(repositoryClass="EventBundle\Repository\Event")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Event implements GroupVisbilityInterface
{
    use SoftDeleteableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    public $id;

    /**
     * @ORM\Column(type="string",length=255)
     */
    public $name;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @var \DateTime
     */
    public $date;

    /**
     * @ORM\Column(type="boolean")
     */
    public $manualChanged=false;


    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    public $location;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    public $info;

    /**
     * @var Comment[]
     * @ORM\ManyToMany(targetEntity="App\Entity\Comment")
     * @ORM\OrderBy({"created" = "ASC"})
     */
    public $comments;


    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    public $created;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    public $updated;


    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    public $updatedBy;

    /**
     * @var EventVote[]
     * @ORM\OneToMany(targetEntity="EventBundle\Entity\EventVote",mappedBy="event",cascade={"persist","remove"})
     */
    public $votes;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @var User
     */
    public $owner;

    /**
     * @ORM\Column(type="boolean")
     */
    public $disableImpulse=false;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    public $maxPersons = 0;


    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    public $public;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Group")
     */
    public $group;

    /**
     * @ORM\Column(type="boolean")
     */
    public $disableAnswer=false;

    /**
     * @ORM\Column(type="boolean")
     */
    public $archived=false;

    /**
     * @var RepeatingEvent $repeatingEvent
     * @ORM\ManyToOne(targetEntity="RepeatingEvent",inversedBy="events")
     * @ORM\JoinColumn(name="repeating_event_id", referencedColumnName="id",nullable=true,columnDefinition="char(36) COLLATE utf8mb4_unicode_ci")
     */
    public $repeatingEvent;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function onUpdate()
    {
        $this->updated = new \DateTime();
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    public function getFormattedDate($format){
        if(!$this->date instanceof \DateTime){
            return 'In Planung';
        }else{
            setlocale(LC_ALL,'de_DE');
            return strftime($format,$this->date->getTimestamp());
        }
    }
}