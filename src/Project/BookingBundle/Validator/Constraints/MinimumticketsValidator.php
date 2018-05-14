<?php
namespace Project\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MinimumticketsValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint)
	{
		if($value <= 0)
		{
			$this->context->addViolation($constraint->message);
		}
	}
}