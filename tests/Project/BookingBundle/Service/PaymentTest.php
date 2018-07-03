<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 02/07/2018
 * Time: 10:27
 */

namespace Tests\Project\BookingBundle\Service;


use PHPUnit\Framework\TestCase;
use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Service\Payment;
use Symfony\Component\HttpFoundation\Request;

class PaymentTest extends TestCase
{

    public function testIsPayment()
    {
        $payment = new Payment('xxxx');

        $booking = new Booking();
        $booking->setTotalPrice(16);

        $request = new Request();

        $payment->isPayment($request, $booking);

        dump($this->getObjectAttribute($booking, 'orderId'));
    }
}