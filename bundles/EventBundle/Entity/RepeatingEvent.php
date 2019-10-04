<?php


namespace EventBundle\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EventBundle\Enums\RepeatingType;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

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
     * @ORM\Column(name="id", type="guid")
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

    public function __construct()
    {
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
        }else{
            return new \DateInterval("P1M");
        }
    }
}