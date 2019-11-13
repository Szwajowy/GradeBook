<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use App\Form\Type\SubjectgroupType;
use App\Form\Type\TeacherType;

class SubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('teacher', CollectionType::class, [
                'entry_type' => TeacherType::class,
                'label' => "Nauczyciele",
                'by_reference' => true,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('save', SubmitType::class,[
                'label' => 'Utwórz',
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['no-validation']
        ]);
    }
}