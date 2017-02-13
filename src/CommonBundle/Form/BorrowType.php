<?php

namespace CommonBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use AdminBundle\Services\CopiesGetterService;
use CommonBundle\Form\DataTransformers\CopyNumberTransformer;
use Doctrine\Common\Persistence\ObjectManager;

class BorrowType extends AbstractType
{
    private $manager;



       public function __construct(ObjectManager $manager)
       {
           $this->manager = $manager;
       }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('beginDate', DateType::class, array(
                'label' => 'Date d\'emprunt',
                'widget' => 'single_text',
                'placeholder' => array(
                    'day' => 'Jour',
                    'month' => 'Mois',
                    'year' => 'Année'
                ),
            ))
            ->add('game', EntityType::class, array(
                'class' => 'CommonBundle:Game',
                'choice_label' => 'name',
                'label' => 'Jeu Emprunté',
                'placeholder' => 'Choisissez un jeu',
                'mapped' => false
            ))
            ->add('copy', EntityType::class, array(
                'class' => 'CommonBundle:Copy',
                'choice_label' => 'reference',
                'label' => 'Exemplaire emprunté',
                'placeholder' => 'Choisissez un exemplaire',
                'mapped' => false
            ))
            ->add('member', EntityType::class, array(
                    'class' => 'CommonBundle:Member',
                    'choice_label' => function ($member) {
                        return $member->getFirstName() . ' ' . $member->getLastName();
                    },
                    'label' => "Emprunteur"
                )
            )
            ->get('copy')
            ->addModelTransformer(new CopyNumberTransformer($this->manager));

//        $listener = function (FormEvent $event) {
//            $copyFrom = $event->getData()['copy'];
////            dump($copyFrom);die;
//            $beginDate = $event->getData()['beginDate'];
//            $game = $event->getData()['game'];
//            $member = $event->getData()['member'];
//
//            $copy = $this->manager->getRepository('CommonBundle:Copy')->findById($copyFrom);
////            dump($copy[0]->getId());die;
//            $form = [
//                "beginDate" => $beginDate,
//                "game" => $game,
//                "copy" => (string)$copy[0]->getId(),
//                "member" => $member,
//
//            ];
//            $event->setData($form);
//        };
//
//        $builder->addEventListener(FormEvents::PRE_SUBMIT, $listener);


    }
}
