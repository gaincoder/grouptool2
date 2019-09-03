<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 28.07.2017
 * Time: 20:45
 */

namespace App\Repository;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Group as Entity;
use Symfony\Component\Security\Core\Security;

class Group implements GroupRepositoryInterface
{


    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Security
     */
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * @return Entity[]
     */
    public function findAllOrdered()
    {

        $dql = 'SELECT i FROM ' . Entity::class . ' i ';
        $query = $this->entityManager->createQuery($dql);
        return $query->execute();

    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }


}