<?php
namespace Project\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MaxTicketsSoldValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint)
	{
		if($value >= 1000)
		{
			$this->context->addViolation($constraint->message);
		}
	}
}