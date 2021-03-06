<?php
namespace Project\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MaxTicketsSold extends Constraint
{
	public $message = "Le nombre maximum de tickets a été vendu pour la date sélectionnée.";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }


}

