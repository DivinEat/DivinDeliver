<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Category;
use App\Validator\FileIsAnImage;
use App\Validator\UniqueMenuTitle;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Security;

class MenuType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;   
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'menu.title',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Enter a title !'
                    ]),
                    new UniqueMenuTitle()
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('q')->select('c')
                        ->from(Category::class, 'c')
                        ->where("c.store = :store_id")
                        ->setParameter("store_id", $this->security->getUser()->getStores()->first()->getId());
                },
                'choice_label' => 'title',
                'label' => 'category.word',
                'multiple' => true
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => false,
                'image_uri' => false,
                'constraints' => [
                    new Image()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Menu::class
        ]);
    }
}
