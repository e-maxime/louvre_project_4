<?php

namespace Project\BookingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('firstName' TextType::class)
            ->add('birthday', BirthdayType::class, array('format' => 'ddMMyyyy'))
            ->add('country', CountryType::class)
            ->add('dayToVisit', DateType::class, array('format' => 'ddMMyyyy'))
            ->add('typeOfTicket', ChoiceType::class)
            ->add('reducePrice', CheckboxType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Project\BookingBundle\Entity\Visitor'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'project_bookingbundle_visitor';
    }


}
