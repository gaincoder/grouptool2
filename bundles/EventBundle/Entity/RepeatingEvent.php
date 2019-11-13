<?php


namespace EventBundle\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EventBundle\Enums\RepeatingType;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="EventBundle\Repository\RepeatingEvent")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class RepeatingEvent
{
    use SoftDeleteableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid",options={"collation":"utf8_unicode_ci"})
     * @ORM\GeneratedValue(strategy="UUID")
     */
    public $id;

    /**
     * @var Event[]
     * @ORM\OneToMany(targetEntity="EventBundle\Entity\Event",mappedBy="repeatingEvent")
     */
    public $events;

    /**
     * @ORM\Column(type="smallint")
     */
    public $type;

    /**
     * @ORM\Column(type="string",length=255)
     */
    public $name;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @var \DateTime
     */
    public $start;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    public $location;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    public $info;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\User",cascade={"remove"})
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
     * @var User[]
     * @ORM\ManyToMany(targetEntity="App\Entity\User",cascade={"remove"})
     *  @ORM\JoinTable(name="repeatevent_notifications",
     *      joinColumns={@ORM\JoinColumn(name="repeat_event_id", referencedColumnName="id",columnDefinition="char(36) COLLATE utf8_unicode_ci")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id",referencedColumnName="id")}
     *      )
     */
    public $notifications;

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
        $this->events = array();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function onUpdate()
    {
        $this->updated = new \DateTime();
    }


    public function getFormattedDate($format){
        if(!$this->start instanceof \DateTime){
            return 'In Planung';
        }else{
            setlocale(LC_ALL,'de_DE');
            return strftime($format,$this->start->getTimestamp());
        }
    }

    public function getTypeAsText(){
        $list = array_flip(RepeatingType::getList());
        return $list[$this->type];
    }

    public function getInterval()
    {
        if($this->type == RepeatingType::WEEKLY){
            return new \DateInterval("P7D");
        }elseif($this->type == RepeatingType::SECONDWEEKLY){
            return new \DateInterval("P14D");
        }elseif($this->type == RepeatingType::THIRDWEEKLY){
            return new \DateInterval("P21D");
        }elseif($this->type == RepeatingType::FOURWEEKLY){
            return new \DateInterval("P28D");
        }elseif($this->type == RepeatingType::MONTHLY){
            return new \DateInterval("P1M");
        }
    }

    public function getFutureEvents(\DateTime $start, $hideArchived = false){
        return array_filter($this->events->toArray(),function($event) use($start,$hideArchived){
            if($hideArchived && $event->archived){
                return false;
            }
            return $event->date > $start;
        });
    }

    public function userBecomesNotification(UserInterface $user){
        return array_search($user,$this->notifications->toArray());
    }
}