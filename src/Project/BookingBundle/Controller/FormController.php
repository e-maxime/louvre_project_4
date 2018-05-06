<?php
namespace Project\BookingBundle\Controller;

use Project\BookingBundle\Entity\Order;
use Project\BookingBundle\Entity\Ticket;
use Project\BookingBundle\Entity\Visitor;
use Project\BookingBundle\Form\TicketType;
use Project\BookingBundle\Form\OrderType;
use Project\BookingBundle\Form\VisitorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class FormController extends Controller
{
	public function indexAction()
	{
		return $this->render('ProjectBookingBundle:Booking:index.html.twig');
	}

	public function ticketAction(Request $request)
	{
		$ticket = new Ticket();

		$ticketForm = $this->createForm(TicketType::class, $ticket, array(
																'action' => $this->generateUrl('project_booking_order'), 
																'method' => 'POST')
																);


		return $this->render('ProjectBookingBundle:Booking:ticket.html.twig', array('ticketForm' => $ticketForm->createView()));		
	}

	public function orderAction(Request $request)
	{
		/*

		for($i = 0; $i < $nbTickets; $i++)
		{
			$ticket = new Ticket();
			$order->getVisitors();
		}

		


		if ($request->isMethod('POST'))
		{
			$orderForm->handleRequest($request);

			if($orderForm->isSubmitted() && $orderForm->isValid())
			{
				$em = $this->getDoctrine()->getManager();
				$em->persist($orderForm);
				$em->flush();
				return $this->render('ProjectBookingBundle:Form:payment.html.twig');
			}
		}*/

		$ticket = new Ticket();

		$ticketForm = $this->createForm(TicketType::class, $ticket, array(
																'action' => $this->generateUrl('project_booking_order'), 
																'method' => 'POST')
																);

		if($request->isMethod('POST'))
		{
		

			

			$ticketForm->handleRequest($request);
			
				dump($ticketForm->getData());

				$nbOfTickets = $ticketForm->getData()->getNbTickets();
				dump($nbOfTickets);
				/*
				return $this->redirectToRoute('project_booking_order');

		array('orderForm' => $orderForm->createView(),)
				*/
			$order = new Order();

			for($i = 0; $i < $nbOfTickets; $i++)
			{
				$order->addVisitor(new Visitor());
			}

		$orderForm = $this->createForm(OrderType::class, $order);

		return $this->render('ProjectBookingBundle:Booking:order.html.twig', array('orderForm' => $orderForm->createView(), 'nbOfTickets' => $nbOfTickets));
			
		}

		return $this->redirectToRoute('project_booking_ticket');
	}

	public function paymentAction(Request $request)
	{
		return $this->render('ProjectBookingBundle:Booking:payment.html.twig');
	}
}