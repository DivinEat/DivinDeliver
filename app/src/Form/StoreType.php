<?php

namespace App\Form;

use App\Entity\Store;
use App\Validator\RestaurateurHasNoStore;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StoreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'name'
            ])
            ->add('storeIdFakeUberEat', TextType::class, [
                'label' => 'store.id_fake_ubereat',
                'required'   => false
            ])
            ->add('storeIdFakeDeliveroo', TextType::class, [
                'label' => 'store.id_fake_deliveroo',
                'required'   => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Store::class,
            'validation_groups' => new RestaurateurHasNoStore(),
        ]);
    }
}
