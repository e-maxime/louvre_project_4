<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 03/07/2018
 * Time: 10:39
 */

namespace Tests\Project\BookingBundle\Service;


use PHPUnit\Framework\TestCase;
use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Service\MailSender;

class MailSenderTest extends TestCase
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testSendBookingConfirmation()
    {
        $mailer = $this
            ->getMockBuilder('\Swift_Mailer')
            ->disableOriginalConstructor()
            ->getMock();

        $twig = $this
            ->getMockBuilder('\Twig_Environment')
            ->disableOriginalConstructor()
            ->getMock();

        $booking = new Booking();

        $mailSender = new MailSender($mailer, $twig);

        $this->assertTrue(true, $mailSender->sendBookingConfirmation($booking));
    }//OK
}