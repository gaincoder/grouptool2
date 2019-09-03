<?php

namespace CompanyBundle\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class CompanyFormType extends AbstractType
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
            ->add('registrationsTo', EntityType::class, array(
                'class' => User::class,
                'multiple' => true,
                'choice_label' => 'username',
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
            'data_class' => 'CompanyBundle\Entity\Company',
        ));
    }
}