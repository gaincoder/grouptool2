<?php

namespace App\Form;

use App\Entity\User;
use App\Enums\Roles;
use App\Services\RoleCollector;
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
    /**
     * @var RoleCollector
     */
    private $roleCollector;

    public function __construct(Security $checker, RoleCollector $roleCollector)
    {
        $this->checker = $checker;
        $this->roleCollector = $roleCollector;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => false, 'attr' => ['placeholder' => 'Name']))
            ->add('selectable', null, array('label' => 'Wählbar für Sichtbarkeit'))
            ->add('public', null, array('label' => 'Mitglieder dürfen beitreten'))
            ->add('roles', ChoiceType::class, array(
                'multiple' => true,
                'choices' => $this->roleCollector->getList(),
                'translation_domain' => 'roles',
                'required' => false,
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