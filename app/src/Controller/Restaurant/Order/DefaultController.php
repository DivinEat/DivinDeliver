<?php


namespace App\Controller\Restaurant\Order;


use App\Entity\Order;
use App\Repository\OrderRepository;
use App\SDK\UberEats\OrderSDK;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\SDK\Deliveroo\OrderSDK as DeliverooOrderSDK;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/orders", name="order_")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(Request $request, OrderRepository $orderRepository): Response
    {
        $choice = $request->query->get('choice') ?? "ALL";

        $choice = strtoupper($choice);

        if ($choice !== 'ALL')
            $orders = $orderRepository->findBy(['currentState' =>  $choice]);
        else
            $orders = $orderRepository->findAll();

        return $this->render('restaurant/order/index.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     */
    public function show(Order $order): Response
    {
        if ($this->getUser()->getStores()->first()->getId() !== $order->getStore()->getId())
            throw $this->createAccessDeniedException();

        return $this->render('restaurant/order/show.html.twig', [
            'order' => $order,
        ]);
    }
}
