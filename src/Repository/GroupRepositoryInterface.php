<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 03.07.2019
 * Time: 21:57
 */

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;

interface GroupRepositoryInterface
{
    /**
     * @return \App\Entity\Group[]
     */
    public function findAllOrdered();

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface;
}