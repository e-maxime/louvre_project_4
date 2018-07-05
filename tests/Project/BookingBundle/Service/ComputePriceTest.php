<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 02/07/2018
 * Time: 09:17
 */

namespace Tests\Project\BookingBundle\Service;


use PHPUnit\Framework\TestCase;
use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Entity\Visitor;
use Project\BookingBundle\Service\ComputePrice;

class ComputePriceTest extends TestCase
{
    /**
     * @param $fullDay
     * @param $age
     * @param $expected
     * @dataProvider getTotalProvider
     */
    public function testGetTotal($fullDay, $age, $expected)
    {
        $computePrice = new ComputePrice();

        $booking = new Booking();
        $booking->setDayToVisit(new \DateTime());
        $booking->setTypeOfTicket($fullDay);

        $ticket1 = new Visitor();
        $ticket1->setBirthday((new \DateTime('-' . $age . ' years')));
        $ticket1->setReducePrice(false);

        $booking->addVisitor($ticket1);

        $computePrice->getTotal($booking);

        $this->assertEquals($expected, $booking->getTotalPrice());
    }//OK

    public function getTotalProvider()
    {
        return [
            ['fullDay' => true, 'age' => 25, 'expected' => 16],
            ['fullDay' => false, 'age' => 25, 'expected' => 8]
        ];
    }
}