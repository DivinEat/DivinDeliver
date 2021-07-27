<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Category;
use App\Validator\FileIsAnImage;
use App\Validator\UniqueItemTitle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ItemType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categoryRepository = $this->em->getRepository(Category::class);
        $listCategory = $categoryRepository->findAll();

        $builder
            ->add('title', TextType::class, [
                'label' => 'item.title',
                'constraints' => [
                    new UniqueItemTitle()
                ]
            ])
            ->add('priceInfo', IntegerType::class, [
                'label' => 'item.price'
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'label' => 'category.word'
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => false,
                'image_uri' => false,
                'constraints' => [
                    new FileIsAnImage()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
