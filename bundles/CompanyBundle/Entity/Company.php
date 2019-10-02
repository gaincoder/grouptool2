<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 28.07.2017
 * Time: 20:41
 */

namespace CompanyBundle\Entity;

use App\Entity\Group;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Company
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
    public $name;


    /**
     * @var Group
     * @ORM\OneToOne(targetEntity="App\Entity\Group")
     */
    public $group;

    /**
     * @var User[]
     * @ORM\ManyToMany(targetEntity="App\Entity\User")
     */
    public $registrationsTo;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    public $shortText;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    public $longText;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    public $logoPath;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    public $contactData;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    public $contactEmail;


    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    public $website;


    public function __toString()
    {
        return $this->name;
    }

    public function getLogoWebPath()
    {
        return 'upload/'.$this->logoPath;
    }

}