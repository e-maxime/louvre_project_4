<?php

namespace Project\BookingBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Project\BookingBundle\Entity\Visitor;
use Project\BookingBundle\Form\BookingType;
use Project\BookingBundle\Form\VisitorType;
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
     * @param EntityManager $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/reserver", name="booking")
     */
    public function bookingAction(Request $request, BookingManager $bookingManager)
    {
        $booking = $bookingManager->init();

        $bookingForm = $this->createForm(BookingType::class, $booking);

        $bookingForm->handleRequest($request);

        if($bookingForm->isSubmitted() && $bookingForm->isValid())
        {
            $bookingManager->generateTickets($booking);
            $booking = $bookingForm->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($booking);
            dump($booking);

            return $this->redirectToRoute('visitor');
        }

        return $this->render('Booking/booking.html.twig', array('bookingForm' => $bookingForm->createView()));
    }

    /**
     * @param Request $request
     * @param EntityManager $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws OptimisticLockException
     * @Route("/informations_visiteurs", name="visitor")
     */
    public function visitorAction(Request $request)
    {
        $visitor = new Visitor();
        $visitorForm = $this->createForm(VisitorType::class, $visitor);

        $visitorForm->handleRequest($request);
        if($visitorForm->isSubmitted() && $visitorForm->isValid())
        {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($visitor);
//            $entityManager->flush();

            return $this->redirectToRoute('payment');
        }
        return $this->render('Booking/visitor.html.twig', array('visitorForm' => $visitorForm->createView()));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/payer", name="payment")
     */
    public function paymentAction()
    {
        return $this->render('Booking/payment.html.twig');
    }
}