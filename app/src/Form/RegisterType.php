<?php

namespace App\Form;

use App\Entity\User;
use App\Validator\UniqueEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'form.firstname',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'form.lastname',
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.email',
                'constraints' => [
                    new UniqueEmail()
                ]
            ])
            ->add('stores', CollectionType::class, [
                'entry_type' => StoreType::class,
                'entry_options' => ['label' => false],
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
