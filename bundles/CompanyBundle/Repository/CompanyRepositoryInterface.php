<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 03.07.2019
 * Time: 21:57
 */

namespace CompanyBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;

interface CompanyRepositoryInterface
{
    /**
     * @return \CompanyBundle\Entity\Company[]
     */
    public function findAllOrdered();

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface;
}