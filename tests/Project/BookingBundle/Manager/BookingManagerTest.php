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
    }

    protected function setUp()
    {
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\SessionInterface')->disableOriginalConstructor()->getMock();
        $entityManagerInterface = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')->disableOriginalConstructor()->getMock();
        $payment = $this->getMockBuilder('Project\BookingBundle\Service\Payment')->disableOriginalConstructor()->getMock();
        $mailSender = $this->getMockBuilder('Project\BookingBundle\Service\MailSender')->disableOriginalConstructor()->getMock();
        $validatorInterface = $this->getMockBuilder('Symfony\Component\Validator\Validator\ValidatorInterface')->disableOriginalConstructor()->getMock();
        $this->bookingManager = new BookingManager($session, $entityManagerInterface, $payment, $mailSender, $validatorInterface);
    }

    /**
     * @param $fullDay
     * @param $age
     * @param $expected
     *
     * @dataProvider computePriceProvider
     */
    public function testComputePrice($fullDay, $age, $expected)
    {
        $booking = new Booking();
        $booking->setDayToVisit(new \DateTime());
        $booking->setTypeOfTicket($fullDay);

        $ticket1 = new Visitor();
        $ticket1->setBirthday((new \DateTime('-' . $age . ' years')));

        $booking->addVisitor($ticket1);

        $this->bookingManager->computePrice($booking);
        $this->assertEquals($expected, $booking->getTotalPrice());


    }

    public function computePriceProvider()
    {
        return [
            ['fullDay' => true, 'age' => 25, 'expected' => 16],
            ['fullDay' => false, 'age' => 25, 'expected' => 8]
        ];
    }

    public function testGenerateTickets()
    {
        $booking = new Booking();
        $booking->setNbTickets(3);

        $this->bookingManager->generateTickets($booking);
        $this->assertEquals(3, $booking->getVisitors()->count());
    }
}