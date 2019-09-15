<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 24.07.2017
 * Time: 14:49
 */

namespace App\Entity;


use CompanyBundle\Entity\Company;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    public $telegramUsername;


    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    public $telegramChatId;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="CompanyBundle\Entity\Company")
     */
    public $company;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    public $firstname;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    public $lastname;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Group",inversedBy="users")
     * @ORM\JoinTable(name="fos_user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\Column(type="smallint",nullable=true)
     */
    public $approval;

    /**
     * @var array
     * @ORM\Column(type="simple_array",nullable=true)
     */
    public $mails;


    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @ORM\PrePersist
     */
    public function disable()
    {
        $this->setEnabled(false);
    }

    public function telegramSupported()
    {
        if (strlen((string)$this->telegramChatId) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getPublicGroups()
    {
        return array_filter($this->groups->toArray(),function($group){
            return $group->public;
        });
    }

    private function getNotPublicGroups()
    {
        return array_filter($this->groups->toArray(),function($group){
            return $group->public !== true;
        });
    }

    public function setPublicGroups($groups)
    {
        $this->groups = array_merge($groups,$this->getNotPublicGroups());
    }

}