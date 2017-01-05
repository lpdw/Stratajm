<?php

namespace CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

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
                ->add('ageMax')
                ->add('rules')
                ->add('releaseDate')
                ->add('themes')
                ->add('types')
                ->add('publisher', EntityType::class, array(
                  'class' => 'CommonBundle:Publisher',
                  'choice_label' => 'name'
                ))
                ->add('nbcopies', IntegerType::class, array('label' => 'Nombre d\'exemplaires', 'mapped' => false));
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
