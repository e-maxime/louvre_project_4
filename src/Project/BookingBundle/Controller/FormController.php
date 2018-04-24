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
use Symfony\Component\HttpFoundation\Request;


class FormController extends Controller
{
	public function indexAction()
	{
		return $this->render('ProjectBookingBundle:Form:index.html.twig');
	}

	public function bookingAction(Request $request)
	{
		

		$form = $this->get('form.factory')->createBuilder(FormType::class)
			->add('name', TextType::class, array('label' => 'Nom :'))
			->add('firstName', TextType::class, array('label' => 'Prénom :'))
			->add('birthday', DateType::class, array('label' => 'Date de naissance :'))
			->add('country', CountryType::class, array('label' => 'Pays d\'origine :'))
			->add('email', EmailType::class, array('label' => 'Adresse email :'))
			->add('dayToVisit', DateType::class, array('label' => 'Jour de visite :'))
			->add('allDay', RadioType::class, array('label' => 'Billet journée'))
			->add('halfDay', RadioType::class, array('label' => 'Billet demi-journée (à partir de 14h)'))
			->add('reducePrice', CheckboxType::class, array('required' => false, 'label' => 'Tarif réduit'))
			->add('pay', SubmitType::class, array('label' => 'Réserver'))
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

		return $this->render('ProjectBookingBundle:Form:form.html.twig', array(
			'form' => $form->createView(),
		));
	}
}