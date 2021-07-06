<?php

namespace App\Controller\Restaurant;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default_index", methods={"GET"})
     */
    public function index()
    {
        return $this->render('restaurant/default/index.html.twig', [
            'user' => $this->getUser(),
            'items' => count($this->getUser()->getStores()->first()->getItems()),
            'categories' => count($this->getUser()->getStores()->first()->getCategories()),
            'users' => count($this->getUser()->getStores()->first()->getUsers()),
            'menus' => count($this->getUser()->getStores()->first()->getMenus()),
            'orders' => count($this->getUser()->getStores()->first()->getOrders()),
        ]);
    }
}
