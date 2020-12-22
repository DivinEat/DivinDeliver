<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('entree', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'label' => 'Entrée',
                'required' => false
            ])
            ->add('main', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'label' => 'Plat'
            ])
            ->add('dessert', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'label' => 'Dessert',
                'required' => false
            ])
            ->add('drink', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'label' => 'Boisson'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
