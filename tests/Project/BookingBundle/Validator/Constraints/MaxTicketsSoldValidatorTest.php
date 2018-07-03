<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 28/06/2018
 * Time: 10:05
 */

namespace Tests\Project\BookingBundle\Validator\Constraints;


use PHPUnit\Framework\TestCase;
use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Validator\Constraints\MaxTicketsSoldValidator;
use Symfony\Component\Validator\Constraint;

class MaxTicketsSoldValidatorTest extends TestCase
{
    public function testMaxTicketsValidate()
    {
        $entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')->disableOriginalConstructor()->getMock();
        $maxTickets = new MaxTicketsSoldValidator($entityManager);

        $booking = new Booking();
        $booking->setDayToVisit(new \DateTime());
        $booking->setNbTickets(5);

        $maxTickets->validate($booking, Constraint::class);

        $this->assertAttributeLessThanOrEqual(1000, $this->getResult() ,$this->maxTickets, "Ok");
    }
}