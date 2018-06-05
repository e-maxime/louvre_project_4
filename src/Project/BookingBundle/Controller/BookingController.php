<?php

namespace Project\BookingBundle\Controller;

use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Form\BookingType;
use Project\BookingBundle\Form\BookingVisitorsType;
use Project\BookingBundle\Manager\BookingManager;
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

        $visitorForm = $this->createForm(BookingVisitorsType::class, $booking);

            $visitorForm->handleRequest($request);

            if ($visitorForm->isSubmitted() && $visitorForm->isValid()) {


                $bookingManager->computePrice($booking);

                return $this->redirectToRoute('payment');
            }
            return $this->render('Booking/visitor.html.twig', array('visitorForm' => $visitorForm->createView()));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/payer", name="payment")
     */
    public function paymentAction(Request $request, BookingManager $bookingManager)
    {
        $booking = $bookingManager->getCurrentBooking();
        dump($booking);

        if($request->isMethod('POST')){

            //Gérer paiement

            // Si paiement OK
              // j'enregistre ma commande en bdd (generer un numero de commande)
              // j'envoie le mail
              // je redirige vers page confirmation
            // Sinon pb paiement
               // message flash error et on laisse courir

        }
        return $this->render('Booking/payment.html.twig', array('booking' => $booking));
    }
}