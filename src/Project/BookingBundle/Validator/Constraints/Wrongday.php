<?php
namespace Project\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Wrongday extends Constraint
{
	public $message = "Le musée est fermé le Mardi, le Dimanche, le 1er Mai, le 1er Novembre et le 25 Décembre.";

	public function validatedBy()
	{
		return get_class($this).'Validator';
	}
}

