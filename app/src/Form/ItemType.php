<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Category;
use App\Validator\FileIsAnImage;
use App\Validator\UniqueItemTitle;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class ItemType extends AbstractType
{
    private $em;
    private $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
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
            ->add('priceInfo', NumberType::class, [
                'label' => 'item.price',
                'scale' => 2,
                'html5' => true,
                'attr' => array(
                    'min' => 0,
                    'step' => '.01',
                ),
                'constraints' => [
                    new PositiveOrZero()
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('q')->select('c')
                        ->from(Category::class, 'c')
                        ->where("c.store = :store_id")
                        ->setParameter("store_id", $this->security->getUser()->getStores()->first()->getId());
                },
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
