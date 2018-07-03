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

    public function testGenerateTickets()
    {
        $booking = new Booking();
        $booking->setNbTickets(3);

        $this->bookingManager->generateTickets($booking);
        $this->assertEquals(3, $booking->getVisitors()->count());
    }
}