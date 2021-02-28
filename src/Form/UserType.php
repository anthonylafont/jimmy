<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = [
            User::ROLE_ADMIN => User::ROLE_ADMIN,
            User::ROLE_USER => User::ROLE_USER
        ];

        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => $roles,
                'multiple' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
