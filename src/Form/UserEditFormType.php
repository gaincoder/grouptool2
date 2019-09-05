<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\User;
use App\Enums\Roles;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class UserEditFormType extends AbstractType
{

    protected $checker;

    public function __construct(Security $checker)
    {
        $this->checker = $checker;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array('label' => 'Username', 'attr' => ['placeholder' => 'Name']))
            ->add('company', EntityType::class, array('label' => 'Firma', 'class' => 'CompanyBundle\Entity\Company'))
            ->add('firstname', null, array('label' => 'Vorname'))
            ->add('lastname', null, array('label' => 'Nachname'))
            ->add('email', EmailType::class, array('label' => 'E-Mail'))

            ->add('groups', EntityType::class, array(
                'label' => 'Gruppen',
                'multiple' => true,
                'class' => Group::class,
                'attr' => ['data-select' => 'true']
            ))
            ->add('enabled',CheckboxType::class,['label'=>false, 'required'=>false ,'attr' => ['data-toggle'=>'toggle', 'data-on'=>'aktiviert','data-off'=>'deaktiviert',"data-onstyle"=>"success", "data-offstyle"=>"danger"]]);

    }

    public function getName()
    {
        return 'app_edit_user';
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\User',
            'cascade_validation' => true,
        ));
    }
}