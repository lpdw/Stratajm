<?php

namespace CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use AdminBundle\Services\CopiesGetterService;


class BorrowType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


      $copiesgetter = $options['copiesgetter'];

      $builder
        ->add('beginDate', DateType::class, array (
          'placeholder' => array(
            'day'=>'Jour',
            'month'=>'Mois',
            'year'=>'Année'
          ),
          'format' => 'dd MM yyyy'
         ))
        ->add('game', EntityType::class, array(
          'class' => 'CommonBundle:Game',
          'choice_label' => 'name',
          'label' => 'Jeu Emprunté',
          'placeholder' => 'Choisissez un jeu',
          'mapped' => false
        ))
        ->add('member', EntityType::class, array(
          'class' => 'CommonBundle:Member',
          'choice_label' => function($member) {
            return $member->getFirstName().' '.$member->getLastName();
          },
          'label' => "Emprunteur"
          )
        )
        ->addEventListener(
          FormEvents::PRE_SUBMIT,
          function (FormEvent $event) {
            $borrow = $event->getData();
            $form = $event->getForm();


            if(!$borrow) {
              return;
            }
            dump($borrow['game']);

            /**
            * TODO : à partir du game (id) récupérée, récupérer la liste des exempaires du jeu
            **/

           die;
            $form->add('copy', EntityType::class, array(
                  'class' => 'CommonBundle:Copy',
                  'choice_label' => 'reference',
                  'label' => 'Copie empruntée',
                  'placeholder' => 'Choisissez une copie',
                  'mapped' => false
                ));

          //   if ($borrow['game'] != "Choisissez un jeu") {
          //     $form->add('copy', EntityType::class, array(
          //       'class' => 'CommonBundle:Copy',
          //       'choice_label' => 'reference',
          //       'label' => 'Copie empruntée',
          //       'placeholder' => 'Choisissez une copie',
          //       'mapped' => false
          //     ));
          //   }
          //   else {
          //     unset($borrow['game']);
          //     $event->setData($borrow);
          //   }
          //
        }
          )
          ->getForm();
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CommonBundle\Entity\Borrow'
        ));
        $resolver->setRequired('copiesgetter');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'commonbundle_borrow';
    }


}
