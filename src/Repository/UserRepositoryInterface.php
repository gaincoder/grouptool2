<?php

namespace App\Repository;

use App\Entity\User as Entity;
use Doctrine\ORM\EntityManagerInterface;

interface UserRepositoryInterface
{
    /**
     * @return Entity[]
     */
    public function findAllOrdered();

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface;
}