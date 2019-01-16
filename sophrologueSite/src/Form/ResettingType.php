<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class ResettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('mail')
            // ->add('password')
            // ->add('roles')
            // ->add('username')
            // ->add('token')
            // ->add('passwordRequestedAt')
            ->add('password', RepeatedType::class, [
                'type'=> PasswordType::class,
                'first_option'=> ['label'=> 'Nouveau mot de passe'],
                'second_option'=> ['label'=> 'Confirmez le mot de passe'],
                'invalid_message'=> 'les 2 mots de passe ne sont pas identitiques !'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
