<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'user.first_name'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'user.last_name'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('roles', ChoiceType::class, [
                'multiple' => false,
                'choices'  => $options['roles_list']
            ])
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                     return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                     return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'roles_list' => null,
        ]);
    }
}