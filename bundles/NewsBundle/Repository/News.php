<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 28.07.2017
 * Time: 20:45
 */

namespace NewsBundle\Repository;


use Doctrine\ORM\EntityRepository;

class News extends EntityRepository
{


    /**
     * @return \NewsBundle\Entity\News[]
     */
    public function findTopFive($groups)
    {

        $query = $this->createQueryBuilder('n')
            ->orderBy('n.date', 'DESC');
        $query
            ->andWhere('n.group IN(:groups) OR n.group IS NULL')
            ->setParameter('groups',$groups);
        $query
            ->setMaxResults(5);
        return $query->getQuery()->execute();

    }

}