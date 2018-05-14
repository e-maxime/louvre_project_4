<?php
namespace Project\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Minimumtickets extends Constraint
{
	public $message = "Vous devez réserver pour une personne minimum.";

	public function validatedBy()
	{
		return get_class($this).'Validator';
	}
}

