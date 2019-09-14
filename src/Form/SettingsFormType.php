<?php

namespace App\Form;

use EmailBundle\Enums\Mails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class SettingsFormType extends AbstractType
{

    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'required' => false,
                'first_options' => array('label' => 'Neues Passwort'),
                'second_options' => array('label' => 'Passwort bestätigen'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
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