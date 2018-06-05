<?php

namespace Project\BookingBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * BookingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookingRepository extends EntityRepository
{
    public function findTotalTicketsByDayToVisit($dayToVisit, $limit = null)
    {
        //$query = $this->_em->createQuery('SELECT SUM(b.nbTickets) FROM ProjectBookingBundle:Booking b WHERE b.dayToVisit = :dayToVisit');
        //$query->setParameter('dayToVisit', $dayToVisit);
        $qb = $this->createQueryBuilder('b')
            ->select('SUM(b.nbTickets)')
            ->where('b.dayToVisit = :day')
            ->setParameter('day', $dayToVisit);

          $query =   $qb->getQuery();

        return $query->getSingleScalarResult();
    }
/*
    public function findTotalJoinTicketsByDayToVisit($dayToVisit)
    {
        $query = $this->createQueryBuilder('b')
            ->select('COUNT(v) as compteur')
            ->innerJoin('b.visitors', 'v')
            ->where('b.dayToVisit = :day')
            ->setParameter('day', $dayToVisit)
            ->getQuery();

        return $query->getSingleScalarResult();
    }
*/
}
