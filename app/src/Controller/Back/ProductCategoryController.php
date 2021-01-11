<?php

namespace App\Controller\Back;

use Exception;
use App\Entity\ProductCategory;
use App\Form\ProductCategoryType;
use App\Repository\ProductCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/productcategory", name="productcategory_")
 */
class ProductCategoryController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(ProductCategoryRepository $productCategoryRepository): Response
    {
        return $this->render('back/product_category/index.html.twig', [
            'productCategories' => $productCategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     */
    public function show(ProductCategory $productCategory): Response
    {
        return $this->render('back/product_category/show.html.twig', [
            'productCategory' => $productCategory,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $productCategory = new ProductCategory();
        $form = $this->createForm(ProductCategoryType::class, $productCategory);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($productCategory);
            $entityManager->flush();

            return $this->redirectToRoute('admin_productcategory_show', [
                'id' => $productCategory->getId()
            ]);
        }

        return $this->render('back/product_category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProductCategory $productCategory): Response
    {
        $form = $this->createForm(ProductCategoryType::class, $productCategory);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_productcategory_edit', [
                'id' => $productCategory->getId()
            ]);
        }

        return $this->render('back/product_category/edit.html.twig', [
            'form' => $form->createView(),
            'productCategory' => $productCategory
        ]);
    }

    /**
     * @Route("/delete/{id}/{token}", name="delete", methods={"GET"})
     */
    public function delete(ProductCategory $productCategory, $token)
    {
        if (!$this->isCsrfTokenValid('delete_productcategory' . $productCategory->getName(), $token)) {
            throw new Exception('Invalid CSRF Token');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($productCategory);
        $em->flush();

        return $this->redirectToRoute('admin_productcategory_index');
    }
}