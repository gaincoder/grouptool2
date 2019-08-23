<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 03.07.2019
 * Time: 21:57
 */

namespace InfoBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;

interface InfoRepositoryInterface
{
    /**
     * @return \InfoBundle\Entity\Info[]
     */
    public function findAllOrdered();

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface;
}