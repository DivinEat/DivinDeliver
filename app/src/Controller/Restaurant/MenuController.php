<?php

namespace App\Controller\Restaurant;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use App\Service\MenuUberEatsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/menus", name="menu_")
 */
class MenuController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(MenuRepository $menuRepository)
    {
        return $this->render('restaurant/menu/index.html.twig', [
            'menus' => $menuRepository->getMenusByUser($this->getUser())
        ]);
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     */
    public function show(Menu $menu)
    {
        if ($this->getUser()->getStores()->first()->getId() !== $menu->getStore()->getId())
            throw $this->createAccessDeniedException();

        return $this->render('restaurant/menu/show.html.twig', [
            'menu' => $menu
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function new(Request $request)
    {
        $menu = new Menu();

        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $menu->setStore($this->getUser()->getStores()->first());

            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();

            $this->addFlash('success', 'Menu créé.');

            return $this->redirectToRoute('restaurant_menu_index', [
                'id' => $menu->getId()
            ]);
        }

        return $this->render('restaurant/menu/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function edit(Menu $menu, Request $request)
    {
        if ($this->getUser()->getStores()->first()->getId() !== $menu->getStore()->getId())
            throw $this->createAccessDeniedException();

        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Menu modifié.');

            return $this->redirectToRoute('restaurant_menu_edit', [
                'id' => $menu->getId()
            ]);
        }

        return $this->render('restaurant/menu/edit.html.twig', [
            'form' => $form->createView(),
            'menu' => $menu
        ]);
    }

    /**
     * @Route("/delete/{id}/{token}", name="delete", methods={"GET"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function delete(Menu $menu, $token)
    {
        if ($this->getUser()->getStores()->first()->getId() !== $menu->getStore()->getId())
            throw $this->createAccessDeniedException();

        if (!$this->isCsrfTokenValid('delete_menu' . $menu->getId(), $token)) {
            throw new Exception('Invalid CSRF Token');
        }

        $this->addFlash('success', 'Menu supprimé.');

        $em = $this->getDoctrine()->getManager();
        $em->remove($menu);
        $em->flush();

        return $this->redirectToRoute('restaurant_menu_index');
    }

    /**
     * @Route("/push", name="push_deliver", methods={"GET"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function pushMenu(MenuUberEatsService $menuUberEatsService)
    {
        $storeId = $this->getUser()->getStores()->first()->getStoreIdFakeUberEat();
        $menuUberEatsService->upload($storeId, $storeId);

        $this->addFlash('success', 'Envoi effectué.');

        return $this->redirectToRoute('restaurant_menu_index');
    }

    /**
     * @Route("/fetch/{deliver}", name="fetch_deliver", methods={"GET"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function fetchMenus(MenuUberEatsService $menuUberEatsService, string $deliver)
    {
        $store = $this->getUser()->getStores()->first();
      
        if ($deliver === 'ubereats')
            $storeId = $store->getStoreIdFakeUberEat();
        else
            $storeId = $store->getStoreIdFakeDeliveroo();

        $menuUberEatsService->resetMenus($store, $deliver);
        $menuUberEatsService->fetch($storeId, $deliver);

        $this->addFlash('success', 'Récupération effectuée.');

        return $this->redirectToRoute('restaurant_menu_index');
    }
}
