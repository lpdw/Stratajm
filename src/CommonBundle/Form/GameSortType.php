<?php

namespace CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;

class GameSortType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('reinitialiser_les_filtres', ResetType::class,array('label' => "Réinitialiser les filtres" ))
        ->add('trier_par', ChoiceType::class, array(
'choices' => array(
        'Date de publication (ancienne à récente)' => 'publication_asc',
        'Date de publication (récente à ancienne)' => 'publication_desc',
        'Date d\'ajout (ancienne à récente)' => 'ajout_asc',
        'Date d\'ajout (récente à ancienne)' => 'ajout_desc',
    )))
        ->add('duree', ChoiceType::class, array(
              'placeholder' => 'Durée du jeu',
              'label' => 'Durée de jeu' ,
              'choices' => array(
                  'Courte (<=30 min)' => 0,
                  'Moyenne (30-45min)' => 1,
                  'Longue (~1 heure)' => 2,
                  'Très long (+ 1 heure)' => 3,
              )))
        ->add('country', EntityType::class, array(
              'class' => 'CommonBundle:Country',
              'label' => 'Origine du jeu' ,
              'choice_label' => 'name',
              'expanded' => true,
              'multiple' => true))

        ->add('players', EntityType::class, array(
              'class' => 'CommonBundle:Players',
              'label' => 'Nombre de joueurs' ,
              'choice_label' => 'name',
              'expanded' => true,
              'multiple' => true))

        ->add('editeur', EntityType::class, array(
              'class' => 'CommonBundle:Publisher',
              'label' => 'Éditeurs' ,
              'choice_label' => 'name',
              'expanded' => true,
              'multiple' => true))

        ->add('authors', EntityType::class, array(
              'class' => 'CommonBundle:Author',
              'label' => 'Auteurs' ,
              'choice_label' => 'name',
              'expanded' => true,
              'multiple' => true))

        ->add('categorie', EntityType::class, array(
              'class' => 'CommonBundle:Type',
              'label' => 'Types de jeu' ,
              'choice_label' => 'name',
              'expanded' => true,
              'multiple' => true))


        ->add('themes', EntityType::class, array(
              'class' => 'CommonBundle:Theme',
              'label' => 'Thèmes' ,
              'choice_label' => 'name',
              'expanded' => true,
              'multiple' => true))

        ->add('congestion', EntityType::class, array(
              'class' => 'CommonBundle:Congestion',
              'label' => 'Encombrement du jeu' ,
              'choice_label' => 'name',
              'expanded' => true,
              'multiple' => true));
    }
}
