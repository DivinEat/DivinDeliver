<?php

namespace App\Controller\Back;

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
        return $this->render('back/menu/index.html.twig', [
            'menus' => $menuRepository->findAll()
        ]);
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     */
    public function show(Menu $menu)
    {
        return $this->render('back/menu/show.html.twig', [
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();

            $this->addFlash('green', 'Menu créé.');

            return $this->redirectToRoute('admin_menu_show', [
                'id' => $menu->getId()
            ]);
        }

        return $this->render('back/menu/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function edit(Menu $menu, Request $request)
    {
        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('green', 'Menu modifié.');

            return $this->redirectToRoute('admin_menu_edit', [
                'id' => $menu->getId()
            ]);
        }

        return $this->render('back/menu/edit.html.twig', [
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
        if (!$this->isCsrfTokenValid('delete_menu' . $menu->getTitle(), $token)) {
            throw new Exception('Invalid CSRF Token');
        }

        $this->addFlash('red', 'Menu supprimé.');

        $em = $this->getDoctrine()->getManager();
        $em->remove($menu);
        $em->flush();

        return $this->redirectToRoute('admin_menu_index');
    }

    /**
     * @Route("/push/ubereats", name="push_ubereats", methods={"GET"})
     */
    public function pushUberEatsMenu(MenuUberEatsService $menuUberEatsService)
    {
        $menuUberEatsService->upload();

        $this->addFlash('green', 'Envoi effectué');

        return $this->redirectToRoute('admin_menu_index');
    }
}