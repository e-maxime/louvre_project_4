<?php

namespace Project\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class HourExceedsValidator extends ConstraintValidator
{
    const MAX_HOUR_FOR_FULLDAY = 14;
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $date = new \DateTime('NOW', new \DateTimeZone('Europe/Paris'));

        if ($value->getDayToVisit()->format('d:m:Y') == $date->format('d:m:Y') &&
            $date->format('H') >= self::MAX_HOUR_FOR_FULLDAY &&
            $value->getTypeOfTicket() === true)
        {
            $this->context->addViolation($constraint->message);
        }
    }
}