<?php
namespace Project\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;


class FormController extends Controller
{
	public function bookingAction(Request $request)
	{
		

		$form = $this->get('form.factory')->createBuilder(FormType::class)
			->add('name', TextType::class)
			->add('firstName', TextType::class)
			->add('birthday', DateType::class)
			->add('country', CountryType::class)
			->add('email', EmailType::class)
			->add('dayToVisit', DateType::class)
			->add('allDay', RadioType::class)
			->add('halfDay', RadioType::class)
			->add('reducePrice', CheckboxType::class, array('required' => false))
			->add('nbTickets', IntegerType::class)
			->add('pay', SubmitType::class)
			->getForm();

		if ($request->isMethod('POST'))
		{
			$form->handleRequest($request);

			if($form->isValid())
			{
				$request->getSession()->getFlashBag()->add('notice', 'Votre réservation à bien été prise en compte. Vous recevrez un email récapitulatif.');

				return $this->redirectToRoute('project_booking_homepage');
			}
		}

		return $this->render('ProjectBookingBundle:Form:index.html.twig', array(
			'form' => $form->createView(),
		));
	}
}