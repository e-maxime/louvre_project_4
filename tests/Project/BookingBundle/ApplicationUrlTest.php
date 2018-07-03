<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 28/06/2018
 * Time: 08:39
 */

namespace Tests\Project\BookingBundle;


use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Manager\BookingManager;
use Project\BookingBundle\Service\Payment;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationUrlTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     * @param $url
     */
    public function testPageIsSuccessful($url)
    {
        $booking = new Booking();
        $booking->setEmail('prenom.nom@domaine.com');
        $booking->setDayToVisit(new \DateTime());
        $booking->setTypeOfTicket(true);
        $booking->setNbTickets(1);

        $client = self::createClient();
        $client->getContainer()->get('session')->set(BookingManager::SESSION_CURRENT_BOOKING, $booking);

        $client->request('GET', $url);



        $this->assertTrue($client->getResponse()->isSuccessful());
    }



    public function urlProvider()
    {
        return array(
            array('/'),
            array('/informations_visiteurs'),
            array('/payer'),
//            array('/confirmation_commande'),
        );
    }
}