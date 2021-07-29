<?php

namespace App\Controller\Restaurant;

use Exception;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ItemRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{

    private $translator;
    private $itemRepository;

    public function __construct(TranslatorInterface $translator, ItemRepository $itemRepository)
    {
        $this->translator = $translator;
        $this->itemRepository = $itemRepository;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('restaurant/category/index.html.twig', [
            'categories' => $categoryRepository->getCategoriesByUser($this->getUser()),
        ]);
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        if ($this->getUser()->getStores()->first()->getId() !== $category->getStore()->getId())
            throw $this->createAccessDeniedException();

        return $this->render('restaurant/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setStore($this->getUser()->getStores()->first());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('category.created'));

            return $this->redirectToRoute('restaurant_category_index', [
                'id' => $category->getId()
            ]);
        }

        return $this->render('restaurant/category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function edit(Request $request, Category $category): Response
    {
        if ($this->getUser()->getStores()->first()->getId() !== $category->getStore()->getId())
            throw $this->createAccessDeniedException();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', $this->translator->trans('category.updated'));

            return $this->redirectToRoute('restaurant_category_edit', [
                'id' => $category->getId()
            ]);
        }

        return $this->render('restaurant/category/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/delete/{id}/{token}", name="delete", methods={"GET"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function delete(Category $category, $token)
    {
        if ($this->getUser()->getStores()->first()->getId() !== $category->getStore()->getId())
            throw $this->createAccessDeniedException();

        if (!$this->isCsrfTokenValid('delete_category' . $category->getId(), $token)) {
            throw new Exception('Invalid CSRF Token');
        }
        
        if (!empty($this->itemRepository->findBy(['store' => $this->getUser()->getStores()->first()->getId(), 'category' => $category->getId()]))) {
            $this->addFlash('danger', "Can't delete a category which is given to one or more items.");
            return $this->redirectToRoute('restaurant_category_index');
        }

        $this->addFlash('success', $this->translator->trans('category.deleted'));

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('restaurant_category_index');
    }
}
