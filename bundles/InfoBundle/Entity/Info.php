<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 28.07.2017
 * Time: 20:41
 */

namespace InfoBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Info
{
    use SoftDeleteableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string",length=255)
     */
    public $headline;

    /**
     * @ORM\Column(type="text")
     */
    public $text;

    /**
     * @ORM\Column(type="smallint")
     */
    public $permission = 0;


    /**
     * @ORM\Column(type="boolean")
     */
    public $important = false;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    public $created;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
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

    public function __construct(string $username)
    {
        $this->created = new DateTime();
        $this->updated = new DateTime();
        $this->createdBy = $username;
        $this->updatedBy = $username;

    }

    /**
     * @ORM\PreUpdate()
     */
    public function onUpdate()
    {
        $this->updated = new DateTime();
    }
}