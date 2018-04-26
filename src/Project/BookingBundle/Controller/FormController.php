<?php
namespace Project\BookingBundle\Controller;

use Project\BookingBundle\Entity\Ticket;
use Project\BookingBundle\Entity\Visitor;
use Project\BookingBundle\Form\TicketType;
use Project\BookingBundle\Form\VisitorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class FormController extends Controller
{
	public function indexAction()
	{
		return $this->render('ProjectBookingBundle:Form:index.html.twig');
	}

	public function bookingAction(Request $request)
	{
		$ticket = new Ticket();

		$form = $this->createForm(TicketType::class, $ticket);

		if ($request->isMethod('POST'))
		{
			$form->handleRequest($request);

			if($form->isSubmitted() && $form->isValid())
			{
				$em = $this->getDoctrine()->getManager();
				$em->persist($ticket);
				$em->flush();
				return $this->redirectToRoute('project_booking_visitors');
			}
		}

		return $this->render('ProjectBookingBundle:Form:form.html.twig', array(
			'form' => $form->createView(),
		));
	}

	public function addVisitorsAction(Request $request)
	{
		$visitor = new Visitor();

		$form = $this->createForm(VisitorType::class, $visitor);

		if($request->isMethod('POST'))
		{
			$form->handleRequest($request);

			if($form->isSubmitted && $form->isValid())
			{
				$em = $this->getDoctrine()->getManager();
				$em->persist($ticket);
				$em->flush();
				return $this->redirectToRoute('project_booking_payment');
			}
		}

		return $this->render('ProjectBookingBundle:Form:visitors.html.twig', array('form' => $form->createView(),));
	}
}