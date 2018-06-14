<?php
namespace Project\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Wrongday extends Constraint
{
	public $message = "Le musée est fermé le ce jour.";

	public function validatedBy()
	{
		return get_class($this).'Validator';
	}
}

