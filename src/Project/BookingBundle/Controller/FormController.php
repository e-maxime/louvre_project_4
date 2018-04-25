<?php
namespace Project\BookingBundle\Controller;

use Project\BookingBundle\Entity\Ticket;
use Project\BookingBundle\Form\TicketType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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

			if($form->isValid())
			{
				$em = $this->getDoctrine()->getManager();
				$em->persist($ticket);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice', 'Votre réservation à bien été prise en compte. Vous recevrez un email récapitulatif.');

				return $this->redirectToRoute('project_booking_homepage');
			}
		}

		return $this->render('ProjectBookingBundle:Form:form.html.twig', array(
			'form' => $form->createView(),
		));
	}
}