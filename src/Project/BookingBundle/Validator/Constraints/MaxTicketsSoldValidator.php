<?php

namespace Project\BookingBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Project\BookingBundle\Entity\Booking;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MaxTicketsSoldValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($booking, Constraint $constraint)
    {
        $nbTickets = $this->entityManager
            ->getRepository(Booking::class)
            ->findTotalTicketsByDayToVisit($booking->getDayToVisit());

        $nbCurrentTickets = $nbTickets + $booking->getNbTickets();

        if ($nbCurrentTickets >= 10) {
            $this->context->addViolation($constraint->message);
        }
    }
}