<?php
/**
 * Created by PhpStorm.
 * User: Maxime
 * Date: 22/05/2018
 * Time: 15:15
 */

namespace Project\BookingBundle\Manager;


use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Entity\Visitor;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BookingManager
{
    const SESSION_CURRENT_BOOKING = "oyouvob";


    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }


    public function init()
    {
        $booking = new Booking();
        $this->session->set(self::SESSION_CURRENT_BOOKING, $booking);
        return $booking;

    }

    public function generateTickets(Booking $booking)
    {
        for ($i = 0; $i < $booking->getNbTickets(); $i++) {
            $booking->addVisitor(new Visitor());
        }
    }

    public function getCurrentBooking()
    {
        // TODO : que faire si il n'y a pas de booking en session
        return $this->session->get(self::SESSION_CURRENT_BOOKING);
    }

}