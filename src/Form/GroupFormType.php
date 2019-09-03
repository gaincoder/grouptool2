<?php

namespace App\Form;

use App\Entity\User;
use App\Enums\Roles;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class GroupFormType extends AbstractType
{

    protected $checker;

    public function __construct(Security $checker)
    {
        $this->checker = $checker;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => false, 'attr' => ['placeholder' => 'Name']))
            ->add('roles', ChoiceType::class, array(
                'multiple' => true,
                'choices' => array_flip(Roles::getList()),
                'attr' => ['data-select' => 'true']
            ));

    }

    public function getName()
    {
        return 'app_company';
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Group',
        ));
    }
}