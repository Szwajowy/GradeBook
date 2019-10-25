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

use App\Entity\Gradevalue;
use App\Entity\Gradetype as GradetypeEntity;
use App\Entity\User;

class GradeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gradevalue', EntityType::class, [
                'class' => Gradevalue::class,
                'label' => 'Ocena',
                'choice_label' => 'content'
            ])
            ->add('gradetype', EntityType::class, [
                'class' => GradetypeEntity::class,
                'label' => 'Typ',
                'choice_label' => 'name'
            ])
            ->add('teacher', EntityType::class, [
                'class' => User::class,
                'choices' => $options['teachers'],
                // Funkcja zwracająca imię i nazwisko jako etykieta
                'choice_label' => function($user) {
                    return $user->getForename().' '.$user->getSurname();
                },
            ])
            ->add('save', SubmitType::class, array(
                'label' => 'Utwórz',
            ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['no-validation'],
            'teachers' => null,
        ]);
    }
}