<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 14/06/2018
 * Time: 10:48
 */

namespace Project\BookingBundle\Service;

use Project\BookingBundle\Entity\Booking;


/**
 * Class MailSender
 * @package Project\ProjectBookingBundle\Service
 */
class MailSender
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * MailSender constructor.
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param Booking $booking
     * @return int
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendBookingConfirmation(Booking $booking)
    {
        $message = (new \Swift_Message('Votre réservation pour le musée du Louvre'))
            ->setFrom('reservation@museedulouvre.fr')
            ->setTo($booking->getEmail())
            ->setBody($this->twig->render('Booking/ticket.html.twig', array('booking' => $booking)), 'text/html');

        return $this->mailer->send($message);
    }


}