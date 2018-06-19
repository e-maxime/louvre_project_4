<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 14/06/2018
 * Time: 10:48
 */

namespace Project\BookingBundle\Service;

use Project\BookingBundle\Manager\BookingManager;
use Symfony\Component\DependencyInjection\ContainerInterface;


class MailSender
{
    private $mailer;
    private $container;
    private $bookingManager;

    public function __construct(\Swift_Mailer $mailer, BookingManager $bookingManager, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->container = $container;
        $this->bookingManager = $bookingManager;
    }

    /**
     * @return int
     * @throws \Twig\Error\Error
     */
    public function sendEmail()
    {
        $booking = $this->bookingManager->getCurrentBooking();

        $message = (new \Swift_Message('Votre réservation pour le musée du Louvre'))
            ->setFrom('reservation@museedulouvre.fr')
            ->setTo($booking->getEmail())
            ->setBody($this->container->get('templating')->render('Booking/ticket.html.twig', array('booking' => $booking)), 'text/html');

        return $this->mailer->send($message);
    }


}