<?php
namespace Project\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class HourExceedsValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint)
	{
		$date = new \DateTime('NOW', new \DateTimeZone('Europe/Paris'));

		if($value->getDayToVisit()->format('d:m:Y') == $date->format('d:m:Y') && $date->format('H') >= '10' && $value->getTypeOfTicket() === true)
		{
			$this->context->addViolation($constraint->message);
		}
	}
}

// && $value->getDayToVisit()->format('H') >= '10' && $value->getTypeOfTicket() === true