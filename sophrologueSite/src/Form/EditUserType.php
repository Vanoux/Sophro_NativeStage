<?php

namespace App\Form;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mail', TextType::class, [
                'label' => 'Adresse Email : ',
                'attr' => [
                    'placeholder' => 'Entrez votre nouvelle adresse mail...'
                ]
            ])
            ->add('username', TextType::class, [
                'label' => 'Nom ou Pseudo : ',
                'attr' => [
                    'placeholder' => 'Entrez votre nouveau nom ou pseudo...'
                ]
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
