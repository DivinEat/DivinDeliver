<?php

namespace App\Controller\Back;

use Exception;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('back/category/index.html.twig', [
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

        return $this->render('back/category/show.html.twig', [
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

            $this->addFlash('green', 'Catégorie créée.');

            return $this->redirectToRoute('admin_category_index', [
                'id' => $category->getId()
            ]);
        }

        return $this->render('back/category/new.html.twig', [
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

            $this->addFlash('green', 'Catégorie modifiée.');

            return $this->redirectToRoute('admin_category_edit', [
                'id' => $category->getId()
            ]);
        }

        return $this->render('back/category/edit.html.twig', [
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

        if (!$this->isCsrfTokenValid('delete_category' . $category->getTitle(), $token)) {
            throw new Exception('Invalid CSRF Token');
        }

        $this->addFlash('red', 'Catégorie supprimée.');

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('admin_category_index');
    }
}