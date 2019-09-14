<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 28.07.2017
 * Time: 20:45
 */

namespace PollBundle\Repository;


use Doctrine\ORM\EntityRepository;

class Poll extends EntityRepository
{

    /**
     * @return \PollBundle\Entity\Poll[]
     */
    public function findAllOrdered($groups)
    {
        $query = $this->createQueryBuilder('p')
            ->addSelect('CASE WHEN p.endDate <= NOW() THEN 1 ELSE 0 END as HIDDEN overdue');
        $query
            ->andWhere('p.group IN(:groups) OR p.group IS NULL')
            ->setParameter('groups',$groups);
        $query
            ->addOrderBy('p.closed')
            ->addOrderBy('overdue')
            ->addOrderBy('p.created', 'DESC');
        return $query->getQuery()->execute();

    }


    /**
     * @return \PollBundle\Entity\Poll[]
     */
    public function findTopFive($groups)
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.closed = :closed')
            ->andWhere('p.endDate > NOW()');
        $query
            ->andWhere('p.group IN(:groups) OR p.group IS NULL')
            ->setParameter('groups',$groups);
        $query
            ->setParameter('closed', false)
            ->orderBy('p.created', 'DESC')
            ->setMaxResults(5);
        return $query->getQuery()->execute();

    }

}