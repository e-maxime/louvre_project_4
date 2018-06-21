<?php

namespace Project\BookingBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * VisitorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VisitorRepository extends EntityRepository
{
    public function findBirthday($birthday)
    {
        $queryBuilder = $this->createQueryBuilder('v');

        $queryBuilder->where('v.birthday = :birthday')
            ->setParameter('birthday', $birthday);

        return $queryBuilder->getQuery()->getResult();
    }
}
