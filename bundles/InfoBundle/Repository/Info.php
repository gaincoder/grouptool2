<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 28.07.2017
 * Time: 20:45
 */

namespace InfoBundle\Repository;


use Doctrine\ORM\EntityManagerInterface;
use InfoBundle\Entity\Info as Entity;
use Symfony\Component\Security\Core\Security;

class Info implements InfoRepositoryInterface
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

        $dql .= 'WHERE i.group IN (:groups) OR i.group IS NULL ';

        $dql .= 'ORDER BY i.important DESC';
        $query = $this->entityManager->createQuery($dql);
        $query->setParameter('groups',$this->security->getUser()->getGroups());
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