<?php

namespace App\Repository;

use App\Entity\User as Entity;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserInterface;

interface UserRepositoryInterface
{
    /**
     * @param $companyId
     * @param bool $all
     * @return UserInterface[]
     */
    public function findAllOrdered($companyId, $all = false);

    /**
     * @param $companyId
     * @param bool $all
     * @return UserInterface[]
     */
    public function findAllActive();


    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface;
}