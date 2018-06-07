<?php

namespace Project\BookingBundle\Controller;

use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Form\BookingType;
use Project\BookingBundle\Form\BookingVisitorsType;
use Project\BookingBundle\Manager\BookingManager;
use Stripe\Charge;
use Stripe\Stripe;
use Swift_Mailer;
use Swift_Message;
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/payer", name="payment")
     */
    public function paymentAction(Request $request, BookingManager $bookingManager, Swift_Mailer $mailer)
    {
        $booking = $bookingManager->getCurrentBooking();
        dump($booking);

        if ($request->isMethod('POST')) {

            //Gérer paiement
            $token = $request->request->get('stripeToken');

            Stripe::setApiKey("sk_test_Gthrzae0MHHzsel9j806xSxw");
            Charge::create(array("amount" => $booking->getTotalPrice()*100, "currency" => "usd", "source" => $token, "description" => "Commande Louvre"));

            // Si paiement OK
            // j'enregistre ma commande en bdd (generer un numero de commande)

            // j'envoie le mail
            $message = (new Swift_Message('Votre réservation pour le musée du Louvre'))
                ->setFrom('reservation@museedulouvre.fr')
                ->setTo($booking->getEmail())
                ->setBody($this->renderView('Booking/ticket.html.twig'), 'text/html');

            $mailer->send($message);

            $this->addFlash('success', 'Paiement effectué. Un email vous a été adressé.');
            return $this->redirectToRoute('homepage');

            // Sinon pb paiement
            // message flash error et on laisse courir

        }
        return $this->render('Booking/payment.html.twig', array('booking' => $booking));
    }
}