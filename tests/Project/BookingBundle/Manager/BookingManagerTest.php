<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 25/06/2018
 * Time: 14:39
 */

namespace Tests\Project\BookingBundle\Manager;


use PHPUnit\Framework\TestCase;
use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Entity\Visitor;
use Project\BookingBundle\Manager\BookingManager;
use Project\BookingBundle\Service\ComputePrice;

class BookingManagerTest extends TestCase
{
    /**
     * @var BookingManager
     */
    private $bookingManager;

    public function testInit()
    {

        $booking = $this->bookingManager->init();

        $this->assertInstanceOf(Booking::class, $booking);
    }//OK

    protected function setUp()
    {
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\SessionInterface')->disableOriginalConstructor()->getMock();
        $entityManagerInterface = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')->disableOriginalConstructor()->getMock();
        $payment = $this->getMockBuilder('Project\BookingBundle\Service\Payment')->disableOriginalConstructor()->getMock();
        $mailSender = $this->getMockBuilder('Project\BookingBundle\Service\MailSender')->disableOriginalConstructor()->getMock();
        $validatorInterface = $this->getMockBuilder('Symfony\Component\Validator\Validator\ValidatorInterface')->disableOriginalConstructor()->getMock();
        $computePrice = $this->getMockBuilder(ComputePrice::class)->disableOriginalConstructor()->getMock();
        $this->bookingManager = new BookingManager($session, $entityManagerInterface, $payment, $mailSender, $validatorInterface, $computePrice);
    }

    public function testGenerateTickets()
    {
        $booking = new Booking();
        $booking->setNbTickets(3);

        $this->bookingManager->generateTickets($booking);
        $this->assertEquals(3, $booking->getVisitors()->count());


        $booking->setNbTickets(2);
        $this->bookingManager->generateTickets($booking);
        $this->assertEquals(2, $booking->getVisitors()->count());
    }//OK

    /**
     * @param $fullDay
     * @param $age
     * @param $expected
     * @dataProvider testComputePriceProvider
     */
    public function testComputePrice($fullDay, $reduced, $age, $expected)
    {
        $booking = new Booking();
        $booking->setDayToVisit(new \DateTime('2018-07-25'));
        $booking->setTypeOfTicket($fullDay);

        $ticket1 = new Visitor();
        $ticket1->setBirthday((new \DateTime('-' . $age . ' years')));
        $ticket1->setReducePrice($reduced);

        $booking->addVisitor($ticket1);

        $this->bookingManager->computePrice($booking);

        $this->assertEquals($expected, $booking->getTotalPrice());
    }

    public function testComputePriceProvider()
    {
        return [
            ['fullDay' => Booking::TYPE_FULL_DAY, 'reduced' => Booking::REDUCED_PRICE_NOT_CHECKED,'age' => 25, 'expected' => 16],
            ['fullDay' => Booking::TYPE_FULL_DAY, 'reduced' => Booking::REDUCED_PRICE_NOT_CHECKED, 'age' => 60, 'expected' => 12],
            ['fullDay' => Booking::TYPE_FULL_DAY, 'reduced' => Booking::REDUCED_PRICE_NOT_CHECKED, 'age' => 1, 'expected' => 0],
            ['fullDay' => Booking::TYPE_FULL_DAY, 'reduced' => Booking::REDUCED_PRICE_NOT_CHECKED, 'age' => 5, 'expected' => 8],
            ['fullDay' => Booking::TYPE_FULL_DAY, 'reduced' => Booking::REDUCED_PRICE_CHECKED, 'age' => 30, 'expected' => 10],
            ['fullDay' => Booking::TYPE_FULL_DAY, 'reduced' => Booking::REDUCED_PRICE_CHECKED, 'age' => 5, 'expected' => 8],

            ['fullDay' => Booking::TYPE_HALF_DAY, 'reduced' => Booking::REDUCED_PRICE_NOT_CHECKED, 'age' => 25, 'expected' => 8],
            ['fullDay' => Booking::TYPE_HALF_DAY, 'reduced' => Booking::REDUCED_PRICE_NOT_CHECKED, 'age' => 60, 'expected' => 6],
            ['fullDay' => Booking::TYPE_HALF_DAY, 'reduced' => Booking::REDUCED_PRICE_NOT_CHECKED, 'age' => 1, 'expected' => 0],
            ['fullDay' => Booking::TYPE_HALF_DAY, 'reduced' => Booking::REDUCED_PRICE_NOT_CHECKED, 'age' => 5, 'expected' => 4],
            ['fullDay' => Booking::TYPE_HALF_DAY, 'reduced' => Booking::REDUCED_PRICE_CHECKED, 'age' => 30, 'expected' => 5],
            ['fullDay' => Booking::TYPE_HALF_DAY, 'reduced' => Booking::REDUCED_PRICE_CHECKED, 'age' => 5, 'expected' => 4],

        ];
    }
}