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
    const SESSION_CURRENT_BOOKING = "bookingData";


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
        return $this->session->get(self::SESSION_CURRENT_BOOKING);
    }

    public function computePrice(Booking $booking)
    {
        $priceTotal = 0;
        foreach ($booking->getVisitors() as $visitor) {
            $age = $visitor->getAge();
            if ($age < 4) {
                $price = 0;
            } else if ($age < 12) {
                $price = 8;
            } else if ($age < 60) {
                $price = 16;
            } else {
                $price = 12;
            }

            if($visitor->getReducePrice()  && $price > Booking::PRICE_REDUCED){
                $price = Booking::PRICE_REDUCED;
            }else{
                $visitor->setReducePrice(false);
            }

            if($booking->getTypeOfTicket() == Booking::TYPE_HALF_DAY){
                $price = $price * Booking::PRICE_HALF_DAY_MULTIPLICATOR;
            }

            $visitor->setPrice($price);
            $priceTotal += $visitor->getPrice();
        }
        $booking->setTotalPrice($priceTotal);
    }

}