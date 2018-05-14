<?php
namespace Project\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class HourExceeds extends Constraint
{
	public $message = "Vous ne pouvez pas réserver un billet pour la journée après 14h00.";

	public function validatedBy()
	{
		return get_class($this).'Validator';
	}

	public function getTargets()
	{
	    return self::CLASS_CONSTRAINT;
	}
}

