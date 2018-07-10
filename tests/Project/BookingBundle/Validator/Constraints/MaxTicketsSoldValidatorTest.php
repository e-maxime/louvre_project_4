<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 28/06/2018
 * Time: 10:05
 */

namespace Tests\Project\BookingBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Validator\Constraints\MaxTicketsSold;
use Project\BookingBundle\Validator\Constraints\MaxTicketsSoldValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MaxTicketsSoldValidatorTest extends ValidatorTestAbstract
{
    protected function getValidatorInstance()
    {
//        $entityManagerInterface = $this->getMockBuilder(EntityManagerInterface::class)->disableOriginalConstructor()->getMock();
        return $this
            ->getMockBuilder(MaxTicketsSoldValidator::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testMaxTicketsValidateReturningOk()
    {
        $maxTicketsConstraint = new MaxTicketsSold();
        $maxTicketsValidator = $this->initValidator();

        $booking = new Booking();
        $booking->setDayToVisit(new \DateTime());
        $booking->setNbTickets();

        $constraint = $this
            ->getMockBuilder(Constraint::class)
            ->disableOriginalConstructor()
            ->getMock();

        $maxTicketsValidator->validate($booking, $constraint);
    }
}