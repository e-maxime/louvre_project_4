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

class BookingManager
{

    public function init()
    {
        return new Booking();
    }

    public function generateTickets(Booking $booking)
    {
        for ($i = 0; $i < $booking->getNbTickets(); $i++) {
            $booking->addVisitor(new Visitor());
        }
    }

}