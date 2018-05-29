<?php
namespace Project\BookingBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MaxTicketsSoldValidator extends ConstraintValidator
{
	private $requestStack;
	private $em;

	public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
	{
		$this->requestStack = $requestStack;
		$this->em = $em;
	}

	public function validate($value, Constraint $constraint)
    {

	}
}