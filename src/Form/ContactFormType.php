<?php
namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company', null,['required'=>true,'label'=>false,'attr'=>['placeholder'=>'Firma']])
            ->add('name',null,['required'=>true,'label'=>false,'attr'=>['placeholder'=>'Name']])
            ->add('email',EmailType::class,['required'=>true,'label'=>false,'attr'=>['placeholder'=>'E-Mail']])
            ->add('message',TextareaType::class,['required'=>true,'label'=>false,'attr'=>['placeholder'=>'Ihre Nachricht an uns','rows'=>5]])


        ;
    }


    public function getBlockPrefix()
    {
        return 'app_contact_form';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\ContactForm',
        ));
    }
}