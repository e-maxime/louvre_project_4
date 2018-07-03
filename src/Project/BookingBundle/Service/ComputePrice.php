<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 28/06/2018
 * Time: 12:03
 */

namespace Project\BookingBundle\Service;


use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Manager\BookingManager;

class ComputePrice
{
    public function getTotal(Booking $booking)
    {
        $priceTotal = 0;
        foreach ($booking->getVisitors() as $visitor) {
            $age = $visitor->getAge();
            if ($age < BookingManager::AGE_KIDS) {
                $price = Booking::PRICE_FREE;
            } else if ($age < BookingManager::AGE_TEENAGER) {
                $price = Booking::PRICE_CHILD;
            } else if ($age < BookingManager::AGE_SENIOR) {
                $price = Booking::PRICE_NORMAL;
            } else {
                $price = Booking::PRICE_OLD;
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