<?php
namespace Project\BookingBundle\Controller;

use Project\BookingBundle\Entity\Command;
use Project\BookingBundle\Entity\Ticket;
use Project\BookingBundle\Entity\Visitor;
use Project\BookingBundle\Form\TicketType;
use Project\BookingBundle\Form\CommandType;
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


		// Récupération du nombre de billets vendus
		$repository = $this->getDoctrine()->getManager()->getRepository('ProjectBookingBundle:Ticket');
		$nbOfTickets = $repository->getNbTicketsSold('2018-05-11');
		$nbTickets = $repository->getNbTickets(); 
		
		for($i = 0; $i < $nbTickets; $i++)
		{
			$valeurs = array_column($nbOfTickets, 'nbTickets');
		}

		$somme = array_sum($valeurs);
		dump($somme);
		// fin

		$ticketForm = $this->createForm(TicketType::class, $ticket, array(
																'action' => $this->generateUrl('project_booking_command'), 
																'method' => 'POST')
																);

		return $this->render('ProjectBookingBundle:Booking:ticket.html.twig', array('ticketForm' => $ticketForm->createView()));		
	}

	public function commandAction(Request $request)
	{
		$ticket = new Ticket();

		$ticketForm = $this->createForm(TicketType::class, $ticket, array(
																'action' => $this->generateUrl('project_booking_command'), 
																'method' => 'POST')
																);

		if($request->isMethod('POST'))
		{
			$ticketForm->handleRequest($request);

				if($ticketForm->isValid())
				{
					$nbOfTickets = $ticketForm->getData()->getNbTickets();

					dump($nbOfTickets);

					$session = new Session();
					$session->set('ticketData', $ticket);

					/*$em = $this->getDoctrine()->getManager();
					$em->persist($ticket);*/

					$command = new Command();

					for($i = 0; $i < $nbOfTickets; $i++)
					{
						$command->addVisitor(new Visitor());
					}

					$commandForm = $this->createForm(CommandType::class, $command, array('action' => $this->generateUrl('project_booking_payment'), 'method' => 'POST'));

					return $this->render('ProjectBookingBundle:Booking:command.html.twig', array('commandForm' => $commandForm->createView(), 'nbOfTickets' => $nbOfTickets));
				}
		}

		return $this->render('ProjectBookingBundle:Booking:ticket.html.twig', array('ticketForm' => $ticketForm->createView()));
	}

	public function paymentAction(Request $request)
	{
		$session = new Session();

		$command = new Command();
		
		$commandForm = $this->createForm(CommandType::class, $command, array('action' => $this->generateUrl('project_booking_payment'), 'method' => 'POST'));

		if($request->isMethod('POST'))
		{
			$commandForm->handleRequest($request);

			dump($commandForm->getData());

			if ($commandForm->isValid())
			{
				$session->get('ticketData')->setCommand($command);

				$em = $this->getDoctrine()->getManager();
				$em->persist($session->get('ticketData'));
				$em->persist($command);
				$em->flush();

				return $this->render('ProjectBookingBundle:Booking:payment.html.twig');
			}
			return $this->redirectToRoute('project_booking_command');
		}
		return $this->redirectToRoute('project_booking_ticket');
	}
}