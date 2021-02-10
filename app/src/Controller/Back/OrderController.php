<?php

namespace App\Controller\Back;

use App\Repository\UserRepository;
use App\SDK\UberEats\OrderSDK;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/orders", name="order_")
 */
class OrderController extends AbstractController
{
    /**
     * @var OrderSDK
     */
    protected OrderSDK $orderSDK;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(OrderSDK $orderSDK)
    {
        $this->orderSDK = $orderSDK;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $choice = $request->query->get('choice') ?? "accepted";
        $storeID = $this->getUser()->getStores()->first()->getStoreIdFakeUberEat();

        if("accepted" == $choice) {
            $orders = $this->orderSDK->getActiveCreatedOrders($storeID);
        } else {
            $orders = $this->orderSDK->getCancelOrders($storeID);
        }

        return $this->render('back/order/index.html.twig', ['orders' => $orders, 'choice' => $choice]);
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     */
    public function show(): Response
    {
        return $this->render('back/order/show.html.twig');
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function edit(): Response
    {
        return $this->render('back/order/edit.html.twig');
    }
}