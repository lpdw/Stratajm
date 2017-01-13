<?php

namespace CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
  use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormEvents;

class GameType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('image', FileType::class, array('label' => 'Image (png, jpg)', 'required' => false))
                ->add('name')
                ->add('duration', ChoiceType::class, array(
                    'choices'  => array(
                        'Courte <= 30mn' => 0,
                        'Moyenne 30-45mn' => 1,
                        'Longue ~1h' => 2,
                        'Très longue +1h' => 3,
                    )))
                ->add('ageMin')
                ->add('nbPlayers')
                ->add('price')
                ->add('congestion',EntityType::class,array(
                  'class' => 'CommonBundle:Congestion',
                  'choice_label' => 'name',
                  'expanded' => true,
                  'multiple' => false
                ))
                ->add('rules')
                ->add('explanationsDuration',ChoiceType::class, array(
                    'choices'  => array(
                        '<10 minutes' => 0,
                        '<20 minutes' => 1,
                        '>20 minutes' => 2,
                    )))
                ->add('releaseDate',DateType::class)
                ->add('country',EntityType::class, array(
                  'class' => 'CommonBundle:Country',
                  'choice_label' => 'name',
                  'expanded' => true,
                  'multiple' => false
                ))
                ->add('themes',EntityType::class, array(
                  'class' => 'CommonBundle:Theme',
                  'choice_label' => 'name',
                  'expanded' => false,
                  'multiple' => true
                ))
                ->add('types',EntityType::class, array(
                  'class' => 'CommonBundle:Type',
                  'choice_label' => 'name',
                  'expanded' => true,
                  'multiple' => true
                ))
                ->add('authors', EntityType::class, array(
                  'class' => 'CommonBundle:Author',
                  'choice_label' => 'name',
                  'expanded' => true,
                  'multiple' => true
                ))
                ->add('publishers', EntityType::class, array(
                  'class' => 'CommonBundle:Publisher',
                  'choice_label' => 'name',
                  'expanded' => true,
                  'multiple' => true
                ))
                ->add('nbcopies', IntegerType::class, array(
                  'label' => 'Nombre d\'exemplaires',
                  'mapped' => false,
                ));

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CommonBundle\Entity\Game'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'commonbundle_game';
    }


}
