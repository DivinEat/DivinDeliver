<?php

namespace App\Form;

use App\Entity\User;
use App\Validator\PasswordDigit;
use App\Validator\PasswordLength;
use App\Validator\PasswordLowercase;
use App\Validator\PasswordSpecial;
use App\Validator\PasswordUppercase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match',
                'constraints' => [
                    new PasswordLength(),
                    new PasswordUppercase(),
                    new PasswordDigit(),
                    new PasswordSpecial(),
                    new PasswordLowercase()
                ],
                'first_options' => [
                    'label' => 'form.password'
                ],
                'second_options' => [
                    'label' => 'form.password_confirm'
                ],
                'attr'   =>  [
                    'class'   => 'form-control form-control-user'
                ],
                'options' => ['attr' => ['class' => 'form-control form-control-user']]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
