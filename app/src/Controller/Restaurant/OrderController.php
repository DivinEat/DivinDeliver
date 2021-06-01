<?php

namespace App\Controller\Restaurant;

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
     * @Route("/accept/{id}", name="accept", methods={"GET"})
     */
    public function accept(Request $request): Response
    {
        $this->orderSDK->acceptOrder($request->get('id'));

        return $this->redirectToRoute('restaurant_order_index');
    }

    /**
     * @Route("/deny/{id}", name="deny", methods={"GET"})
     */
    public function deny(Request $request): Response
    {
        $this->orderSDK->denyOrder($request->get('id'));

        return $this->redirectToRoute('restaurant_order_index');
    }

    /**
     * @Route("/cancel/{id}", name="cancel", methods={"GET"})
     */
    public function cancel(Request $request): Response
    {
        $this->orderSDK->cancelOrder($request->get('id'));

        return $this->redirectToRoute('restaurant_order_index');
    }
}
