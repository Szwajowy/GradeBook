<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use App\Entity\Subjectgroup;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('forename', TextType::class, array())
            ->add('surname', TextType::class, array())
            ->add('save', SubmitType::class, array(
                'label' => 'Utwórz',
            )
        );
    }
}