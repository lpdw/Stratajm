<?php

namespace CommonBundle\Form;

use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MembershipType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('amount')
            ->add('paymentMethod', EntityType::class, array(
                'class' => 'CommonBundle:PaymentMethod',
                'choice_label' => 'name'
            ))
            ->add('champcachee', HiddenType::class, array(
                'mapped' => false,
                'attr' => array('class' => 'memberId'),
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CommonBundle\Entity\Membership'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'commonbundle_membership';
    }


}
