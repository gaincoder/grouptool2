<?php

namespace InfoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Security;

class InfoFormType extends AbstractType
{

    protected $checker;

    public function __construct(Security $checker)
    {
        $this->checker = $checker;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('headline', null, array('label' => false, 'attr' => ['placeholder' => 'Überschrift']));

        if ($this->checker->isGranted('ROLE_STAMMI')) {
            $builder
                ->add('permission', ChoiceType::class, [
                    'label' => 'Sichtbar für',
                    'choices' => [
                        'Alle' => 0,
                        'Stamm-Mitglieder' => 1
                    ]
                ]);
            $builder
                ->add('important', CheckboxType::class, array('label' => 'anpinnen', 'required' => false));
        }
        $builder
            ->add('text', TextareaType::class, array('label' => false, 'attr' => ['class' => 'summernote', 'placeholder' => 'Text', 'rows' => 15]));
    }

    public function getName()
    {
        return 'app_info';
    }
}