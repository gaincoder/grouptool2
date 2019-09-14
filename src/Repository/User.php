<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 28.07.2017
 * Time: 20:45
 */

namespace App\Repository;


use CompanyBundle\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User as Entity;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Security;

class User implements UserRepositoryInterface
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
     * @param $companyId
     * @param bool $all
     * @return Entity[]
     */
    public function findAllOrdered($companyId, $all = false)
    {

        $dql = 'SELECT i FROM ' . Entity::class . ' i ';
        if(!$all){
            $dql .= 'WHERE i.company = '.$companyId;
        }
        $query = $this->entityManager->createQuery($dql);
        return $query->execute();

    }

    /**
     * @param $companyId
     * @param bool $all
     * @return Entity[]
     */
    public function findNotApproved($companyId, $all = false)
    {

        $dql = 'SELECT i FROM ' . Entity::class . ' i WHERE i.approval IS NULL AND i.confirmationToken IS NULL ';
        if(!$all){
            $dql .=  'AND i.company = '.$companyId;
        }
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


    /**
     * @param $companyId
     * @param bool $all
     * @return UserInterface[]
     */
    public function findAllActive()
    {
        $dql = 'SELECT i FROM ' . Entity::class . ' i ';
        $dql .= 'WHERE i.enabled = 1';
        $query = $this->entityManager->createQuery($dql);
        return $query->execute();

    }
}