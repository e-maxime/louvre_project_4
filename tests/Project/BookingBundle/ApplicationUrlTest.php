<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 28/06/2018
 * Time: 08:39
 */

namespace Tests\Project\BookingBundle;


use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Entity\Visitor;
use Project\BookingBundle\Manager\BookingManager;
use Project\BookingBundle\Service\Payment;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApplicationUrlTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     * @param $url
     * @param $booking
     * @param $status
     */
    public function testPageIsSuccessful($url, $booking ,$status)
    {
        $client = self::createClient();
        if($booking){
            $client->getContainer()->get('session')->set(BookingManager::SESSION_CURRENT_BOOKING, $booking);
        }

        $client->request('GET', $url);


        $this->assertEquals($status, $client->getResponse()->getStatusCode());
    }



    public function urlProvider()
    {
        $visitorEmpty = new Visitor();

        $visitorNotEmpty = new Visitor();
        $visitorNotEmpty->setName('Enrietto');
        $visitorNotEmpty->setFirstName('Maxime');
        $visitorNotEmpty->setBirthday(new \DateTime('1993-09-09'));
        $visitorNotEmpty->setCountry('FR');
        $visitorNotEmpty->setReducePrice(false);

        $booking = new Booking();
        $booking->setEmail('prenom.nom@domaine.com');
        $booking->setDayToVisit(new \DateTime('2018-07-11'));
        $booking->setTypeOfTicket(true);
        $booking->setNbTickets(1);
        $booking->addVisitor($visitorEmpty);

        $bookingSecondStep = new Booking();
        $bookingSecondStep->setEmail('prenom.nom@domaine.com');
        $bookingSecondStep->setDayToVisit(new \DateTime('2018-07-11'));
        $bookingSecondStep->setTypeOfTicket(true);
        $bookingSecondStep->setNbTickets(1);
        $bookingSecondStep->addVisitor($visitorNotEmpty);

        $bookingWithOrderId = clone $bookingSecondStep;
        $bookingWithOrderId->setOrderId('xxx');


        return array(
            ['/', null, Response::HTTP_OK],
            ['/', $booking, Response::HTTP_OK],
            ['/', $bookingSecondStep, Response::HTTP_OK],
            ['/informations_visiteurs', null, Response::HTTP_NOT_FOUND],
            ['/informations_visiteurs', $booking, Response::HTTP_OK],
            ['/informations_visiteurs', $bookingSecondStep, Response::HTTP_OK],
            ['/payer', null, Response::HTTP_NOT_FOUND],
            ['/payer', $booking, Response::HTTP_NOT_FOUND],
            ['/payer', $bookingSecondStep, Response::HTTP_OK],
            ['/confirmation_commande', null, Response::HTTP_NOT_FOUND],
            ['/confirmation_commande', $booking, Response::HTTP_NOT_FOUND],
            ['/confirmation_commande', $bookingSecondStep, Response::HTTP_NOT_FOUND],
            ['/confirmation_commande', $bookingWithOrderId, Response::HTTP_OK],
        );
    }
}