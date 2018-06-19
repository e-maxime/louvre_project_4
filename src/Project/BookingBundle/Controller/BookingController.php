<?php

namespace Project\BookingBundle\Controller;

use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Form\BookingType;
use Project\BookingBundle\Form\BookingVisitorsType;
use Project\BookingBundle\Manager\BookingManager;
use Project\BookingBundle\Service\MailSender;
use Project\BookingBundle\Service\Payment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class BookingController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('Booking/index.html.twig');
    }

    /**
     * @param Request $request
     * @param BookingManager $bookingManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/reserver", name="booking")
     */
    public function bookingAction(Request $request, BookingManager $bookingManager)
    {
        $booking = $bookingManager->init();

        $bookingForm = $this->createForm(BookingType::class, $booking);

        $bookingForm->handleRequest($request);

        if ($bookingForm->isSubmitted() && $bookingForm->isValid()) {

            $bookingManager->generateTickets($booking);

            return $this->redirectToRoute('visitor');
        }

        return $this->render('Booking/booking.html.twig', array('bookingForm' => $bookingForm->createView()));
    }

    /**
     * @param Request $request
     * @param BookingManager $bookingManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/informations_visiteurs", name="visitor")
     */
    public function visitorAction(Request $request, BookingManager $bookingManager)
    {
        /** @var Booking $booking */
        $booking = $bookingManager->getCurrentBooking();


        dump($booking);

        $visitorForm = $this->createForm(BookingVisitorsType::class, $booking);

        $visitorForm->handleRequest($request);

        if ($visitorForm->isSubmitted() && $visitorForm->isValid()) {


            $bookingManager->computePrice($booking);

            return $this->redirectToRoute('payment');
        }
        return $this->render('Booking/visitor.html.twig', array('visitorForm' => $visitorForm->createView()));
    }

    /**
     * @param Request $request
     * @param BookingManager $bookingManager
     * @param Payment $payment
     * @param MailSender $sendEmail
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Twig\Error\Error
     * @Route("/payer", name="payment")
     */
    public function paymentAction(Request $request, BookingManager $bookingManager, Payment $payment, MailSender $sendEmail)
    {
        $booking = $bookingManager->getCurrentBooking();
        dump($booking);

        if ($request->isMethod('POST')) {

            $executePayment = $bookingManager->executePayment($request, $payment, $sendEmail);

           if($executePayment){
               return $this->redirectToRoute('checked');
           }

           else{
                $this->addFlash('warning', 'Une erreur est survenue lors de l\'opération.');
                return $this->redirectToRoute('payment');
           }
        }
        return $this->render('Booking/payment.html.twig', array('booking' => $booking, 'stripe_public_key' => $this->getParameter('stripe_public_key')));
    }

    /**
     * @param BookingManager $bookingManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/confirmation_commande", name="checked")
     */
    public function checkedAction(BookingManager $bookingManager)
    {
        $booking = $bookingManager->getCurrentBooking();
        $bookingManager->removeCurrentBooking();
        return $this->render('Booking/confirmed.html.twig', array('booking' => $booking));
    }
}