<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

use App\Entity\User;
use App\Entity\Subject;
use App\Entity\Subjectgroup;

use App\Form\Type\SubjectgroupType;
use App\Form\Type\TeacherType;

class SubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                $builder->create('subjectgroup', SubjectgroupType::class, ['by_reference' => true])
                    ->add('name', TextType::class, [
                        'label' => "Nazwa grupy",
                    ])
            )
            ->add('teacher', CollectionType::class, array(
                'entry_type' => TeacherType::class,
                'label' => "Nauczyciele",
                'by_reference' => true,
                'allow_add' => true,
                'allow_delete' => true,
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Utwórz',
            ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['no-validation']
        ]);
    }
}