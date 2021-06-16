<?php

namespace App\Controller\Restaurant;

use App\Repository\ItemRepository;
use App\Repository\MenuRepository;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default_index", methods={"GET"})
     */
    public function index(
        ItemRepository $itemRepository,
        UserRepository $userRepository,
        CategoryRepository $categoryRepository,
        MenuRepository $menuRepository,
        OrderRepository $orderRepository
    ) {
        return $this->render('restaurant/default/index.html.twig', [
            'user' => $this->getUser(),
            'items' => count($itemRepository->findAll()),
            'categories' => count($categoryRepository->findAll()),
            'users' => count($userRepository->findAll()),
            'menus' => count($menuRepository->findAll()),
            'orders' => count($orderRepository->findAll())
        ]);
    }
}
