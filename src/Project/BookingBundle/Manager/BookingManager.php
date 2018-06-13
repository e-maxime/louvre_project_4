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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookingManager
{
    const SESSION_CURRENT_BOOKING = "bookingData";
    const AGE_SENIOR = 60;
    const AGE_TEENAGER = 12;
    const AGE_KIDS = 4;


    /**
     * @var SessionInterface
     */
    private $session;


    /**
     * BookingManager constructor.
     * @param SessionInterface $session
     */
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
        $currentSession = $this->session->get(self::SESSION_CURRENT_BOOKING);

        if (!$currentSession || $currentSession->getEmail() == null)
        {
            throw new NotFoundHttpException('La page demandée n\'existe pas pour le moment car aucune donnée n\'a été envoyé');
        }
        else {
            return $currentSession;
        }
    }

    public function removeCurrentBooking()
    {
        return $this->session->remove(self::SESSION_CURRENT_BOOKING);
    }

    public function computePrice(Booking $booking)
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

    public function executePaiement($request)
    {
        //utilise le service PAiement et fait la charge
        //si paiement ok
          // generer un numero de commande
         //enregistre dans la base
          // envoie le mail
         // return true
        //sinon
           // return false
    }

}