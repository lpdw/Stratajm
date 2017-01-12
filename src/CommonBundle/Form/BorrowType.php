<?php

namespace CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class BorrowType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('beginDate')
          ->add('endDate')
          ->add('copy', EntityType::class, array(
            'class' => 'CommonBundle:Copy',
            'choice_label' => 'reference',
            'label' => 'Copie empruntée'
          ))
          ->add('member', EntityType::class, array(
            'class' => 'CommonBundle:Member',
            'choice_label' => function($member) {
              return $member->getFirstName().' '.$member->getLastName();
            },
            'label' => "Emprunteur"
            )
          )
          ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CommonBundle\Entity\Borrow'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'commonbundle_borrow';
    }


}
