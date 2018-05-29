<?php
namespace Project\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class WrongdayValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint)
	{
		$year = date('Y');
		if(
			$value == new \DateTime('01-05-'.$year) ||
			$value == new \DateTime('01-11-'.$year) ||
			$value == new \DateTime('25-12-'.$year))
		{
			$this->context->buildViolation($constraint->message)->addViolation();
		}
	}
}