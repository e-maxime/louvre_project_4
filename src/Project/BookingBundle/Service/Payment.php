<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 13/06/2018
 * Time: 13:46
 */

namespace Project\BookingBundle\Service;


use Project\BookingBundle\Entity\Booking;
use Stripe\Charge;
use Stripe\Error\Card;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class Payment
 * @package Project\ProjectBookingBundle\Service
 */
class Payment
{
    /**
     * @var
     */
    private $stripeSecretKey;

    /**
     * Payment constructor.
     * @param $stripeSecretKey
     */
    public function __construct($stripeSecretKey)
    {
        $this->stripeSecretKey = $stripeSecretKey;
    }

    /**
     * @param Request $request
     * @param Booking $booking
     *
     * @return bool
     */
    public function isPayment(Request $request, Booking $booking)
    {
        try{
            Stripe::setApiKey($this->stripeSecretKey);
            $charge = Charge::create(array("amount" => $booking->getTotalPrice() * 100, "currency" => "eur", "source" => $request->get('stripeToken'), "description" => "Commande Louvre"));
        }catch(Card $e){
            return false;
        }
        return $charge->id;
    }
}