<?php

namespace App\Form;

use App\Entity\Store;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SettingStoreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name'
            ])
            ->add('storeIdFakeUberEat', TextType::class, [
                'label' => 'Secret Key UberEat'
            ])
            ->add('storeIdFakeDeliveroo', TextType::class, [
                'label' => 'Secret Key Deliveroo'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Store::class
        ]);
    }
}
