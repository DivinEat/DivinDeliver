<?php

namespace App\Form;

use App\Entity\AuthApp;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AuthAppType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('appName', ChoiceType::class, [
                'multiple' => false,
                'choices'  => [
                    'UberEats' => 'uber',
                ]
            ])
            ->add('clientId', TextType::class, [
                'label' => 'Client Id'
            ])
            ->add('clientSecret', TextareaType::class, [
                'label' => 'Client Secret'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AuthApp::class,
        ]);
    }
}
