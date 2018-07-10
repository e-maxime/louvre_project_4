<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 28/06/2018
 * Time: 10:05
 */

namespace Tests\Project\BookingBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;

use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Repository\BookingRepository;
use Project\BookingBundle\Validator\Constraints\MaxTicketsSold;
use Project\BookingBundle\Validator\Constraints\MaxTicketsSoldValidator;


class MaxTicketsSoldValidatorTest extends ValidatorTestAbstract
{
    protected function getValidatorInstance()
    {


        $repository = $this
            ->getMockBuilder(BookingRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findTotalTicketsByDayToVisit'])
            ->getMock();

        $repository->expects($this->once())
            ->method('findTotalTicketsByDayToVisit')
            ->will($this->returnValue(MaxTicketsSoldValidator::MAX_TICKETS_SOLD - 2));


        $entityManagerInterface = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRepository'])
            ->getMock();

        $entityManagerInterface->expects($this->once())
            ->method('getRepository')
            ->with(Booking::class)
            ->will($this->returnValue($repository));

        return new MaxTicketsSoldValidator($entityManagerInterface);
    }

    public function testMaxTicketsValidateReturningOk()
    {
        $maxTicketsConstraint = new MaxTicketsSold();
        $maxTicketsValidator = $this->initValidator();

        $booking = new Booking();
        $booking->setDayToVisit(new \DateTime());
        $booking->setNbTickets(2);


        $maxTicketsValidator->validate($booking, $maxTicketsConstraint);
    }

    public function testMaxTicketsValidateReturningNotOk()
    {
        $maxTicketsConstraint = new MaxTicketsSold();
        $maxTicketsValidator = $this->initValidator($maxTicketsConstraint->message);

        $booking = new Booking();
        $booking->setDayToVisit(new \DateTime());
        $booking->setNbTickets(3);


        $maxTicketsValidator->validate($booking, $maxTicketsConstraint);
    }
}