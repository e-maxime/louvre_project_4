<?php

namespace Project\BookingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class VisitorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Nom :'))
            ->add('firstName', TextType::class, array('label' => 'Prénom :'))
            ->add('birthday', BirthdayType::class, array('label' => 'Date de naissance :', 'format' => 'ddMMyyyy'))
            ->add('country', CountryType::class, array('label' => 'Pays d\'origine :', 'preferred_choices' => 'France'))
            ->add('reducePrice', CheckboxType::class, array('label' => 'Tarif réduit :', 'required' => false))
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
