<?php

namespace Project\BookingBundle\Controller;

use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Form\BookingType;
use Project\BookingBundle\Form\BookingVisitorsType;
use Project\BookingBundle\Manager\BookingManager;
use Project\BookingBundle\Service\ComputePrice;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class BookingController
 * @package Project\ProjectBookingBundle\Controller
 */
class BookingController extends Controller
{
    /**
     * @param Request $request
     * @param BookingManager $bookingManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, BookingManager $bookingManager)
    {
        $booking = $bookingManager->init();

        $bookingForm = $this->createForm(BookingType::class, $booking);

        $bookingForm->handleRequest($request);

        if ($bookingForm->isSubmitted() && $bookingForm->isValid()) {

            $bookingManager->generateTickets($booking);

            return $this->redirectToRoute('visitor');
        }

        return $this->render('Booking/index.html.twig', array('bookingForm' => $bookingForm->createView()));
    }

    /**
     * @param Request $request
     * @param BookingManager $bookingManager
     * @param ComputePrice $computePrice
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/informations_visiteurs", name="visitor")
     */
    public function visitorAction(Request $request, BookingManager $bookingManager, ComputePrice $computePrice)
    {
        /** @var Booking $booking */
        $booking = $bookingManager->getCurrentBooking(BookingManager::NEED_DATA_BOOKING);

        $visitorForm = $this->createForm(BookingVisitorsType::class, $booking);

        $visitorForm->handleRequest($request);

        if ($visitorForm->isSubmitted() && $visitorForm->isValid()) {

            $computePrice->getTotal($booking);

            return $this->redirectToRoute('payment');
        }
        return $this->render('Booking/visitor.html.twig', array('visitorForm' => $visitorForm->createView()));
    }

    /**
     * @param Request $request
     * @param BookingManager $bookingManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @Route("/payer", name="payment")
     */
    public function paymentAction(Request $request, BookingManager $bookingManager)
    {
        $booking = $bookingManager->getCurrentBooking(BookingManager::NEED_DATA_TICKETS);

        if ($request->isMethod('POST')) {

            $executePayment = $bookingManager->executePayment($booking, $request);

            if ($executePayment) {
                return $this->redirectToRoute('checked');
            }

            $this->addFlash('warning', 'Une erreur est survenue lors de l\'opération.');
            return $this->redirectToRoute('payment');
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
        $booking = $bookingManager->getCurrentBooking(BookingManager::NEED_ID_ORDER);
        $bookingManager->removeCurrentBooking();
        return $this->render('Booking/confirmed.html.twig', array('booking' => $booking));
    }
}