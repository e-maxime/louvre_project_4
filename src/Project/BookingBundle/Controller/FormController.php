<?php
namespace Project\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FormController extends Controller
{
	public function indexAction()
	{
		$content = $this->get('templating')->render('ProjectBookingBundle:Form:index.html.twig');

		return new Response($content);
	}
}