<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClassblockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateTimeType::class, [
                'widget' => 'choice',
                'label' => 'Data rozpoczęcia zajęć',
            ])
            ->add('endDate', DateTimeType::class, [
                'widget' => 'choice',
                'label' => 'Data zakończenia zajęć',
            ])
            ->add('type', TextType::class, [
                'label' => 'Typ zajęć',
            ])
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