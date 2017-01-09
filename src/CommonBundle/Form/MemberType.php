<?php

namespace CommonBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, array(
                'label' => 'Prénom :',
            ))
            ->add('lastName', TextType::class, array(
                'label' => 'Nom :',
            ))
            ->add('email', EmailType::class,array(
                'label' => 'Email :',
            ))
            ->add('telNum', TextType::class, array(
                'label' => 'Nom :',
            ))
            ->add('paymentMethod', EntityType::class, array(
                'class' => 'CommonBundle:PaymentMethod',
                'choice_label' => 'name',
                'mapped' => false,
            ))
            ->add('amount', TextType::class, array(
                'mapped' => false,
                'attr' => array('class' => 'montant'),
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CommonBundle\Entity\Member'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'commonbundle_member';
    }


}
