<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 25/06/2018
 * Time: 17:34
 */

namespace Tests\Project\BookingBundle\Controller;

use Project\BookingBundle\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingControllerTest extends WebTestCase
{
    // TESTS FONCTIONNELS

    public function testHomepageIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertSame(1, $crawler->filter('html:contains("Réservation en ligne")')->count());

        echo $client->getResponse()->getContent();
    }

    public function testSendBooking()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('Réserver')->form();
        $form['project_bookingbundle_ticket[email]'] = 'maxime@enrietto.fr';
        $form['project_bookingbundle_ticket[dayToVisit]'] = '2018-06-28';
        $form['project_bookingbundle_ticket[typeOfTicket]'] = 1;
        $form['project_bookingbundle_ticket[nbTickets]'] = '2';
        $client->submit($form);

        $crawler = $client->followRedirect();

        $form1 = $crawler->selectButton('Valider')->form();
        $form1['project_bookingbundle_ticket[visitors][0][name]'] = 'Ki';
        $form1['project_bookingbundle_ticket[visitors][0][firstName]'] = 'Nuts';
        $form1['project_bookingbundle_ticket[visitors][0][birthday]'] = '1993-09-09';
        $form1['project_bookingbundle_ticket[visitors][0][country]'] = 'FR';
        $form1['project_bookingbundle_ticket[visitors][0][reducePrice]'] = false;

        $form1['project_bookingbundle_ticket[visitors][1][name]'] = 'Ven';
        $form1['project_bookingbundle_ticket[visitors][1][firstName]'] = 'Ator';
        $form1['project_bookingbundle_ticket[visitors][1][birthday]'] = '1991-11-09';
        $form1['project_bookingbundle_ticket[visitors][1][country]'] = 'BR';
        $form1['project_bookingbundle_ticket[visitors][1][reducePrice]'] = true;

        $client->submit($form1);

        $client->followRedirect();

        echo $client->getResponse()->getContent();

    }
}
