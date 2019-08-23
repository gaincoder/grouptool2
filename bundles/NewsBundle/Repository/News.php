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
    public function findTopFive($permission = 0)
    {

        $query = $this->createQueryBuilder('n')
            ->orderBy('n.date', 'DESC');
        if ($permission < 1) {
            $query
                ->andWhere('n.permission = 0');
        }
        $query
            ->setMaxResults(5);
        return $query->getQuery()->execute();

    }

}