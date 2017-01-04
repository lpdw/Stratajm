<?php

namespace CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GameSearchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
           // Max lenght : 200 chars
          ->add('searchGame',TextType::class,array('required' => true,'label' => false,'attr' => array(
        'placeholder' => 'Rechercher par nom',
    )))
          ->add('Rechercher', SubmitType::class,array('label'=>' '));


        }


}
