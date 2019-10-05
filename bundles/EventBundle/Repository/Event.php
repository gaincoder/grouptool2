<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 28.07.2017
 * Time: 20:45
 */

namespace EventBundle\Repository;


use Doctrine\ORM\EntityRepository;

class Event extends EntityRepository
{


    public function findFuture($groups)
    {
        $limitDate = new \DateTime('today - 2 days');
        $query = $this->createQueryBuilder('e')
            ->where('e.date >= :limit OR e.date IS NULL')
            ->setParameter('limit',$limitDate);
            $query
                ->andWhere('e.group IN(:groups) OR e.group IS NULL')
                ->setParameter('groups',$groups);
        $query
            ->andWhere('e.archived = 0');
        $query
            ->orderBy('e.date');
        return $query->getQuery()->execute();

    }

    /**
     * @return \EventBundle\Entity\Event[]
     */
    public function findNextFive($groups)
    {
        $query = $this->createQueryBuilder('e')
            ->where('e.date >= NOW()');
        $query
            ->andWhere('e.group IN(:groups) OR e.group IS NULL')
            ->setParameter('groups',$groups);
        $query
            ->andWhere('e.archived = 0');
        $query
            ->orderBy('e.date')
            ->setMaxResults(5);
        return $query->getQuery()->execute();

    }


    /**
     * @return \EventBundle\Entity\Event[]
     */
    public function findNextThree()
    {
        $query = $this->createQueryBuilder('e')
            ->where('e.date >= NOW()')
            ->andWhere('e.permission = 0')
            ->orderBy('e.date')
            ->setMaxResults(3);
        $query
            ->andWhere('e.archived = 0');
        return $query->getQuery()->execute();

    }


    /**
     * @return \EventBundle\Entity\Event[]
     */
    public function findNextFiveForGroup($permission = 0)
    {
        $query = $this->createQueryBuilder('e')
            ->where('e.date >= NOW()')
            ->andWhere('e.public = 0');
        if ($permission < 1) {
            $query
                ->andWhere('e.permission = 0');
        }
        $query
            ->andWhere('e.archived = 0');
        $query
            ->orderBy('e.date')
            ->setMaxResults(5);
        return $query->getQuery()->execute();

    }

}