<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 05/07/2018
 * Time: 09:50
 */

namespace Tests\Project\BookingBundle\Form;


use Project\BookingBundle\Entity\Booking;
use Project\BookingBundle\Form\BookingType;
use Symfony\Component\Form\Test\TypeTestCase;

class BookingTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'email' => 'maxime@enrietto.fr',
            'dayToVisit' => new \DateTime('2018-07-05'),
            'typeOfTicket' => true,
            'nbTickets' => 3,
        );

        $objectToCompare = new Booking();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(BookingType::class, $objectToCompare);

        $object = new Booking();
        $object->setEmail('maxime@enrietto.fr');
        $object->setDayToVisit(new \DateTime());
        $object->setTypeOfTicket(true);
        $object->setNbTickets(3);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        // check that $objectToCompare was modified as expected when the form was submitted
        $this->assertEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}