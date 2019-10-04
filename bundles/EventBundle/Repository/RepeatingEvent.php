<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 28.07.2017
 * Time: 20:45
 */

namespace EventBundle\Repository;


use Doctrine\ORM\EntityRepository;

class RepeatingEvent extends EntityRepository
{


    /**
     * @return \EventBundle\Entity\RepeatingEvent[]
     */
    public function findForGroups($groups)
    {
        $query = $this->createQueryBuilder('e')
            ->where('e.group IN(:groups) OR e.group IS NULL')
            ->setParameter('groups',$groups);
        return $query->getQuery()->execute();

    }


}