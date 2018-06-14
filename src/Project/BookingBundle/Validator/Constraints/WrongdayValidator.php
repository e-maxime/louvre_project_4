<?php
namespace Project\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class WrongdayValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint)
	{
//	    $object = $this->context->getObject();

		$year = date('Y');

        $easterDate  = easter_date($year);

		if(
            $value == new \DateTime('01-01-'.$year) ||
            $value == new \DateTime($easterDate) ||
			$value == new \DateTime('01-05-'.$year) ||
			$value == new \DateTime('08-05-'.$year) ||
			$value == new \DateTime('14-07-'.$year) ||
			$value == new \DateTime('15-08-'.$year) ||
			$value == new \DateTime('01-11-'.$year) ||
			$value == new \DateTime('11-11-'.$year) ||
			$value == new \DateTime('25-12-'.$year))
		{
			$this->context->buildViolation($constraint->message)->addViolation();
		}
	}
}