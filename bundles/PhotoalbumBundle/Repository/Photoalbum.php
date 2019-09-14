<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 28.07.2017
 * Time: 20:45
 */

namespace PhotoalbumBundle\Repository;


use Doctrine\ORM\EntityRepository;

class Photoalbum extends EntityRepository
{


    public function findAllOrdered($groups)
    {
        $query = $this->createQueryBuilder('e');
        $query
            ->andWhere('e.group IN(:groups) OR e.group IS NULL')
            ->setParameter('groups',$groups);
        $query->orderBy('e.id', 'DESC');
        return $query->getQuery()->execute();

    }

}