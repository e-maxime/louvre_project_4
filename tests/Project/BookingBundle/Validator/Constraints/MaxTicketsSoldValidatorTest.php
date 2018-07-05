<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 28/06/2018
 * Time: 10:05
 */

namespace Tests\Project\BookingBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Validator\Constraints\MaxTicketsSoldValidator;
use Symfony\Component\Validator\Constraint;

class MaxTicketsSoldValidatorTest extends TestCase
{
    public function testMaxTicketsValidateReturningOk()
    {
        $entityManagerInterface = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $booking = new Booking();
        $booking->setDayToVisit(new \DateTime());

        $maxTickets = new MaxTicketsSoldValidator($entityManagerInterface);

        $valueCounted = $maxTickets->validate($booking, Constraint::class);

        $this->assertAttributeLessThanOrEqual(MaxTicketsSoldValidator::MAX_TICKETS_SOLD, $valueCounted);


    }
}