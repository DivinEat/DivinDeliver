<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MenuType extends AbstractType
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'name'
            ])
            ->add('entree', EntityType::class, [
                'class' => Product::class,
                'label' => 'product.starter',
                'choices' => $this->productRepository->findByCategory(1),
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('main', EntityType::class, [
                'class' => Product::class,
                'label' => 'product.main',
                'choices' => $this->productRepository->findByCategory(2),
                'choice_label' => 'name'
            ])
            ->add('dessert', EntityType::class, [
                'class' => Product::class,
                'label' => 'product.dessert',
                'choices' => $this->productRepository->findByCategory(3),
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('drink', EntityType::class, [
                'class' => Product::class,
                'label' => 'product.drink',
                'choices' => $this->productRepository->findByCategory(4),
                'choice_label' => 'name'
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
