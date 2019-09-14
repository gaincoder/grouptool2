<?php

namespace PollBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class PollFormType extends AbstractType
{
    protected $checker;

    public function __construct(Security $checker)
    {
        $this->checker = $checker;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Frage'
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Teilnahme möglich bis',
                'widget' => 'single_text'
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Typ',
                'choices' => [
                    'Eine Antwort pro Mitglied' => 0,
                    'Mitglieder dürfen mehere Antworten auswählen' => 1
                ]
            ])
            ->add('allowAdd', null, [
                'label' => 'Andere Mitglieder dürfen neue Antwortmöglichkeiten hinzufügen'
            ]);

        $builder
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
            ]);

        $builder
            ->add('info', TextareaType::class, array('label' => false, 'required' => false, 'attr' => ['class' => 'summernote', 'placeholder' => 'Info', 'rows' => 15]));
        $builder
            ->add('answers', CollectionType::class, [
                'entry_type' => PollAnswerFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PollBundle\Entity\Poll'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'App_poll';
    }


}
