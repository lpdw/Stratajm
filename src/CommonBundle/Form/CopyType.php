<?php

namespace CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class CopyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('reference', TextType::class, array('label' => 'Référence'))
          ->add('status', EntityType::class, array(
            'label' => 'Etat',
            'class' => 'CommonBundle:Status',
            'choice_label' => 'name'
          ))
          ->add('localisation', EntityType::class, array(
            'label' => 'Emplacement',
            'class' => 'CommonBundle:Localisation',
            'choice_label' => 'name'
          ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CommonBundle\Entity\Copy'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'commonbundle_copy';
    }


}
