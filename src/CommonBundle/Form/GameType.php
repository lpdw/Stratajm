<?php

namespace CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
  use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class GameType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('image', FileType::class, array('label' => 'Image (png, jpg)', 'required' => false))
                ->add('board_image', FileType::class, array('label' => 'Plateau imprimable (png, jpg)', 'required' => false))
                ->add('name', TextType::class, array('label' => 'Nom'))
                ->add('duration', ChoiceType::class, array(
                    'label' => 'Durée',
                    'choices'  => array(
                        'Courte <= 30mn' => 0,
                        'Moyenne 30-45mn' => 1,
                        'Longue ~1h' => 2,
                        'Très longue +1h' => 3,
                    )))
                ->add('ageMin', IntegerType::class,array(
                    'label' => 'Âge minimum'
                ))
                ->add('nbPlayers',EntityType::class,array(
                    'label' => 'Nombre de joueurs',
                    'class' => 'CommonBundle:Players',
                    'choice_label' => 'name',
                ))
                ->add('price')
                ->add('congestion',EntityType::class,array(
                    'label' => 'Encombrement',
                    'class' => 'CommonBundle:Congestion',
                    'choice_label' => 'name',

                ))
                ->add('rules',TextareaType::class, array(
                    'attr' => array('class' => 'tinymce'),
                    'label' => 'Règles du jeu'))
                ->add('explanationsDuration',ChoiceType::class, array(
                    'label' => 'Temps d\'explication',
                    'choices'  => array(
                        '<10 minutes' => 0,
                        '<20 minutes' => 1,
                        '>20 minutes' => 2,
                    )))
                ->add('traditional', CheckboxType::class,array(
                    'label' => 'Traditionnel',
                    'attr' => array('class'=>'traditionnel'),
                    'required'=>false))
                ->add('releaseDate', DateTimeType::class, array(
                    'label' => 'Date de sortie',
                    'widget' => 'single_text',
                    'attr' => array('id' => 'datepicker'),
                    'format' => 'yyyy-MM-dd',
                ))
                ->add('country',EntityType::class, array(
                    'label' => 'Pays d\'origine',
                  'class' => 'CommonBundle:Country',
                  'choice_label' => 'name'))
                ->add('themes',EntityType::class, array(
                    'label' => 'Thèmes',
                  'class' => 'CommonBundle:Theme',
                  'choice_label' => 'name',
                  'expanded' => true,
                  'multiple' => true
                ))
                ->add('types',EntityType::class, array(
                    'label' => 'Types',
                  'class' => 'CommonBundle:Type',
                  'choice_label' => 'name',
                  'expanded' => true,
                  'multiple' => true
                ))
                ->add('authors', EntityType::class, array(
                    'label' => 'Auteurs',
                  'class' => 'CommonBundle:Author',
                  'choice_label' => 'name',
                  'expanded' => true,
                  'multiple' => true
                ))
                ->add('publishers', EntityType::class, array(
                    'label' => 'Éditeurs',
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
