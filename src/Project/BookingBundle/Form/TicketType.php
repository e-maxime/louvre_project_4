<?php

namespace Project\BookingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('firstName', TextType::class)
            ->add('birthday', BirthdayType::class, array('format' => 'ddMMyyyy'))
            ->add('country', CountryType::class)
            ->add('email', EmailType::class)
            ->add('dayToVisit', DateType::class, array('format' => 'ddMMyyyy'))
            ->add('typeOfTicket', ChoiceType::class, array('choices' => array('Journée entière' => true, 'Demi-journée' => false)))
            ->add('reducePrice', CheckboxType::class, array('label' => 'Tarif réduit', 'required' => false))
            ->add('nbTickets', IntegerType::class)
            ->add('order', SubmitType::class, array('label' => 'Réserver'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Project\BookingBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'project_bookingbundle_ticket';
    }


}
