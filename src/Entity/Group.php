<?php


namespace App\Entity;


use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_group")
 */
class Group extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    public $public=false;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    public $selectable=false;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User",mappedBy="groups")
     * )
     */
    public $users;

    public function __toString()
    {
        return $this->name;
    }
}