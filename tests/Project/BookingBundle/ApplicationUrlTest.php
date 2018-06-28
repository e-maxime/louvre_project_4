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
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationUrlTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->getContainer()->get('session')->set(BookingManager::SESSION_CURRENT_BOOKING, new Booking());
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        return array(
            array('/'),
            array('/informations_visiteurs'),
            array('/payer'),
            array('/confirmation_commande'),
        );
    }
}