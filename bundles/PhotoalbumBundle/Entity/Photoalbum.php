<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 28.07.2017
 * Time: 20:41
 */

namespace PhotoalbumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="PhotoalbumBundle\Repository\Photoalbum")
 */
class Photoalbum
{
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
     * @ORM\OneToMany(targetEntity="PhotoalbumBundle\Entity\Photo",mappedBy="album")
     */
    public $photos;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Group")
     */
    public $group;


}