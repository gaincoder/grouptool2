<?php

namespace App\Form;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;

class CommentFormType extends AbstractType
{


    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', null, array('label' => false, 'attr' => ['placeholder' => 'Geben Sie ihren Kommentar ein
            ...']))
            ;

    }

    public function getName()
    {
        return 'app_comment';
    }
}