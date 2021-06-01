<?php

namespace App\Controller\Restaurant;

use App\Entity\Item;
use App\Form\ItemType;
use App\Entity\Product;
use App\Form\AuthAppType;
use App\Repository\ItemRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/items", name="item_")
 */
class ItemController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(ItemRepository $itemRepository)
    {
        return $this->render('restaurant/item/index.html.twig', [
            'items' => $itemRepository->getCategoriesByUser($this->getUser())
        ]);
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     */
    public function show(Item $item)
    {
        if ($this->getUser()->getStores()->first()->getId() !== $item->getStore()->getId())
            throw $this->createAccessDeniedException();

        return $this->render('restaurant/item/show.html.twig', [
            'item' => $item
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function new(Request $request)
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $item->setStore($this->getUser()->getStores()->first());

            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            $this->addFlash('green', 'Produit créé.');

            return $this->redirectToRoute('restaurant_item_index', [
                'id' => $item->getId()
            ]);
        }

        return $this->render('restaurant/item/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function edit(Item $item, Request $request)
    {
        if ($this->getUser()->getStores()->first()->getId() !== $item->getStore()->getId())
            throw $this->createAccessDeniedException();

        $form = $this->createForm(ItemType::class, $item);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('green', 'Produit modifié.');

            return $this->redirectToRoute('restaurant_item_edit', [
                'id' => $item->getId()
            ]);
        }

        return $this->render('restaurant/item/edit.html.twig', [
            'form' => $form->createView(),
            'item' => $item
        ]);
    }

    /**
     * @Route("/delete/{id}/{token}", name="delete", methods={"GET"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function delete(Item $item, $token)
    {
        if ($this->getUser()->getStores()->first()->getId() !== $item->getStore()->getId())
            throw $this->createAccessDeniedException();

        if (!$this->isCsrfTokenValid('delete_item' . $item->getTitle(), $token)) {
            throw new Exception('Invalid CSRF Token');
        }

        $this->addFlash('red', 'Produit supprimé.');

        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();

        return $this->redirectToRoute('restaurant_item_index');
    }
}
