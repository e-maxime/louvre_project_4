<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 18/07/2018
 * Time: 11:43
 */

namespace Project\BookingBundle\Service;


use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Manager\BookingManager;

class PriceCalculator
{
    const AGE_SENIOR = 60;
    const AGE_TEENAGER = 12;
    const AGE_KIDS = 4;

    private $price_free;
    private $price_child;
    private $price_normal;
    private $price_old;
    private $price_reduced;
    private $bookingManager;

    public function __construct($price_free, $price_child, $price_normal, $price_old, $price_reduced, BookingManager $bookingManager)
    {
        $this->price_free = $price_free;
        $this->price_child = $price_child;
        $this->price_normal = $price_normal;
        $this->price_old = $price_old;
        $this->price_reduced = $price_reduced;
        $this->bookingManager = $bookingManager;
    }

    public function getTotal()
    {
        $booking = $this->bookingManager->getCurrentBooking();

        $priceTotal = 0;

        foreach ($booking->getVisitors() as $visitor) {
            $age = $visitor->getAge();
            if ($age < self::AGE_KIDS) {
                $price = $this->price_free;
            } else if ($age < self::AGE_TEENAGER) {
                $price = $this->price_child;
            } else if ($age < self::AGE_SENIOR) {
                $price = $this->price_normal;
            } else {
                $price = $this->price_old;
            }

            if($visitor->getReducePrice()  && $price > $this->price_reduced){
                $price = $this->price_reduced;
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