<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 13/06/2018
 * Time: 13:46
 */

namespace Project\BookingBundle\Service;


use Project\BookingBundle\Manager\BookingManager;
use Stripe\Charge;
use Stripe\Stripe;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class Payment
{
    private $container;
    private $bookingManager;
    private $session;

    public function __construct(ContainerInterface $container, BookingManager $bookingManager, SessionInterface $session)
    {
        $this->container = $container;
        $this->bookingManager = $bookingManager;
        $this->session = $session;
    }

    /**
     * @param $token
     */
    public function getPayment($token)
    {
        $booking = $this->bookingManager->getCurrentBooking();

        Stripe::setApiKey($this->container->getParameter('stripe_secret_key'));
        Charge::create(array("amount" => $booking->getTotalPrice() * 100, "currency" => "usd", "source" => $token, "description" => "Commande Louvre"));
    }
}