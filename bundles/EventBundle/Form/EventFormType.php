<?php

namespace EventBundle\Form;

use Doctrine\ORM\EntityRepository;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Security;

class EventFormType extends AbstractType
{

    protected $checker;

    public function __construct(Security $checker)
    {
        $this->checker = $checker;
    }

    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateTimeType::class, array('label' => false, 'required' => false, 'date_widget' => 'single_text',
                'attr' => ['placeholder' => 'Datum'], 'choice_translation_domain' => true))
            ->add('name', null, array('label' => false, 'required' => true, 'attr' => ['placeholder' => 'Name']))
            ->add('location', null, array('label' => false, 'required' => false, 'attr' => ['placeholder' => 'Ort']))
            ->add('disableAnswer', CheckboxType::class, array('label' => 'Teilnahme abschalten', 'required' => false,'help'=>'Benutzer können nicht mehr angeben ob sie teilnemen oder nicht.'))
            ->add('disableImpulse', CheckboxType::class, array('label' => 'Spontan abschalten', 'required' => false,'help' => 'Benutzer können nur noch "dabei" und "nein" wählen'))
                ->add('group', EntityType::class, [
                    'class'=>'App\Entity\Group',
                    'label' => 'Sichtbarkeit einschränken',
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('g')
                            ->where('g.selectable = 1')
                            ->andWhere('g.id IN(:groups)')
                            ->setParameter('groups',$this->checker->getUser()->getGroups())
                            ->orderBy('g.name', 'ASC');
                    },
                ])
            ->add('info', TextareaType::class, array('label' => false, 'required' => false, 'attr' => ['class' => 'summernote', 'placeholder' => 'Info', 'rows' => 15]));
    }

    public function getName()
    {
        return 'app_event';
    }
}