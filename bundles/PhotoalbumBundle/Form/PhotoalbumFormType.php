<?php

namespace PhotoalbumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Security;

class PhotoalbumFormType extends AbstractType
{

    protected $checker;

    public function __construct(Security $checker)
    {
        $this->checker = $checker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => false, 'required' => true, 'attr' => ['placeholder' => 'Name']));


        if ($this->checker->isGranted('ROLE_STAMMI')) {
            $builder
                ->add('permission', ChoiceType::class, [
                    'label' => 'Sichtbar für',
                    'choices' => [
                        'Alle' => 0,
                        'Stamm-Mitglieder' => 1
                    ]
                ]);
        }
    }

    public function getName()
    {
        return 'app_photoalbum';
    }
}