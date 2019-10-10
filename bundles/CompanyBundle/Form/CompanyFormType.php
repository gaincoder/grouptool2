<?php

namespace CompanyBundle\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
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
            ))
            ->add('shortText',null,['required'=>false])
            ->add('cooperation',CheckboxType::class,['label'=>'Kooperationspartner','required'=>false])
            ->add('longText',TextareaType::class,['required'=>false])
            ->add('contactData',null,['label'=>'Kontakt','required'=>false])
            ->add('contactEmail',EmailType::class,['label'=>'E-Mail','required'=>false])
            ->add('website',UrlType::class,['required'=>false])
            ->add('logo',FileType::class,['required'=>false,'mapped'=>false])
        ;

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