<?php

namespace App\Form;

use EmailBundle\Enums\Mails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SettingsFormType extends AbstractType
{

    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mails',ChoiceType::class,[
                'label' => 'Sende mir eine E-Mail wenn...',
                'expanded'=>true,
                'multiple' => true,
                'choices' => Mails::getList(),
                'translation_domain' => 'mails'
            ]);

    }

    public function getName()
    {
        return 'settings_form';
    }
}