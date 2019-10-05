<?php

namespace EventBundle\Form;

use Doctrine\ORM\EntityRepository;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Security;

class SimpleEventFormType extends AbstractType
{


    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateTimeType::class, array('label' => false, 'required' => false, 'date_widget' => 'single_text',
                'attr' => ['placeholder' => 'Datum'], 'choice_translation_domain' => true))
            ->add('location', null, array('label' => false, 'required' => false, 'attr' => ['placeholder' => 'Ort']));
    }

    public function getName()
    {
        return 'app_simple_event';
    }
}