<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 05/07/2018
 * Time: 09:22
 */

namespace Tests\Project\BookingBundle\Validator\Constraints;


use Project\BookingBundle\Validator\Constraints\HourExceeds;
use Project\BookingBundle\Validator\Constraints\HourExceedsValidator;

class HourExceedsValidatorTest extends ValidatorTestAbstract
{
    protected function getValidatorInstance()
    {
        return new HourExceedsValidator();
    }

    public function testHourExceedsOk()
    {
        $hourExceedsConstraint = new HourExceeds();
        $hourExceedsValidator = $this->initValidator();

        $hourExceedsValidator->validate(new \DateTime(), $hourExceedsConstraint);

    }
}