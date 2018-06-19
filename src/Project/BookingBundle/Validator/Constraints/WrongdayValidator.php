<?php
namespace Project\BookingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class WrongdayValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint)
	{
	    $dateSelected = date_format($value, 'Y:m:d');
	    $timeDateSelected = time($dateSelected);

	    $year = intval(date('Y'));

        $easterDate  = easter_date($year);
        $easterDay   = date('j', $easterDate);
        $easterMonth = date('n', $easterDate);
        $easterYear   = date('Y', $easterDate);

        $publicHolidays = array(
            // Dates fixes
            mktime(0, 0, 0, 1,  1,  $year),  // 1er janvier
            mktime(0, 0, 0, 5,  1,  $year),  // Fête du travail
            mktime(0, 0, 0, 5,  8,  $year),  // Victoire des alliés
            mktime(0, 0, 0, 7,  14, $year),  // Fête nationale
            mktime(0, 0, 0, 8,  15, $year),  // Assomption
            mktime(0, 0, 0, 11, 1,  $year),  // Toussaint
            mktime(0, 0, 0, 11, 11, $year),  // Armistice
            mktime(0, 0, 0, 12, 25, $year),  // Noel

            // Dates variables
            mktime(0, 0, 0, $easterMonth, $easterDay + 1,  $easterYear),
            mktime(0, 0, 0, $easterMonth, $easterDay + 39, $easterYear),
            mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear),
        );

        dump($publicHolidays);

        /*foreach ($publicHolidays as $v)
        {
            if($v === $timeDateSelected)
            {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }*/

        for($i = 0; $i < sizeof($publicHolidays); $i++ )
        {
            if ($publicHolidays[$i] === $timeDateSelected)
            {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }


		/*if(
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
		}*/
	}
}