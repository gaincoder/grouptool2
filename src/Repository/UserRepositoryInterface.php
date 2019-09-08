<?php

namespace App\Repository;

use App\Entity\User as Entity;
use Doctrine\ORM\EntityManagerInterface;

interface UserRepositoryInterface
{
    /**
     * @param $companyId
     * @param bool $all
     * @return Entity[]
     */
    public function findAllOrdered($companyId, $all = false);

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface;
}