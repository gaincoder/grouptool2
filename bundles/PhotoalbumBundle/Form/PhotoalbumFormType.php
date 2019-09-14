<?php

namespace PhotoalbumBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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

    }

    public function getName()
    {
        return 'app_photoalbum';
    }
}