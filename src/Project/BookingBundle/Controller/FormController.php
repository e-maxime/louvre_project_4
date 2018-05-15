<?php
namespace Project\BookingBundle\Controller;

use Project\BookingBundle\Entity\Command;
use Project\BookingBundle\Entity\Ticket;
use Project\BookingBundle\Entity\Visitor;
use Project\BookingBundle\Form\TicketType;
use Project\BookingBundle\Form\CommandType;
use Project\BookingBundle\Form\VisitorType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
																'action' => $this->generateUrl('project_booking_visitor'), 
																'method' => 'POST')
																);

		return $this->render('ProjectBookingBundle:Booking:ticket.html.twig', array('ticketForm' => $ticketForm->createView()));		
	}

	public function visitorAction(Request $request)
	{
		$ticket = new Ticket();
		$session = new Session();

		$ticketForm = $this->createForm(TicketType::class, $ticket, array(
																'action' => $this->generateUrl('project_booking_visitor'), 
																'method' => 'POST')
																);

		if($request->isMethod('POST'))
		{
			$ticketForm->handleRequest($request);

				if($ticketForm->isValid())
				{
					$nbOfTickets = $ticketForm->getData()->getNbTickets();
					
					dump($nbOfTickets);

					$session->set('ticketData', $ticket);

					dump($session->get('ticketData'));

					for($i = 0; $i < $nbOfTickets; $i++)
					{
						$ticket->addVisitor(new Visitor());
					}

					
					$visitorForm = $this->createFormBuilder($ticket)
									->setAction($this->generateUrl('project_booking_payment'))
								 	->setMethod('POST')
									->add('visitors', CollectionType::class, array('entry_type' => VisitorType::class, 'entry_options' => array('label' => false), 'allow_add' => true))
            						->add('command', SubmitType::class, array('label' => 'Commander'))
            						->getForm();
					

					return $this->render('ProjectBookingBundle:Booking:formVisitor.html.twig', array('visitorForm' => $visitorForm->createView()));
				}
		}

		return $this->render('ProjectBookingBundle:Booking:ticket.html.twig', array('ticketForm' => $ticketForm->createView()));
	}

	public function paymentAction(Request $request)
	{
		$session = new Session();
		$ticket = new Ticket();
		$visitor = new Visitor();

		$visitorForm = $this->createFormBuilder($ticket)
									->setAction($this->generateUrl('project_booking_payment'))
								 	->setMethod('POST')
									->add('visitors', CollectionType::class, array('entry_type' => VisitorType::class, 'entry_options' => array('label' => false), 'allow_add' => true))
            						->add('command', SubmitType::class, array('label' => 'Commander'))
            						->getForm();

		if($request->isMethod('POST'))
		{
			$visitorForm->handleRequest($request);

				// dump($visitorForm->getData()); // Array collection prit en compte mais les valeurs des champs typeOfTicket, dayToVisit, etc NULL

				// $session->get('ticketData')->setTicket($visitor);
				// dump($session->get('ticketData')); // Données TRUE sauf ArrayCollection

				$em = $this->getDoctrine()->getManager();
				// $em->persist($session->get('ticketData'));
				// $em->flush();

				return $this->render('ProjectBookingBundle:Booking:payment.html.twig');
			
			
			// return $this->redirectToRoute('project_booking_visitor');
		}
		return $this->redirectToRoute('project_booking_ticket');
	}
}