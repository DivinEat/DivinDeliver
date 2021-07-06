<?php

namespace App\Controller\Restaurant\Order;

use App\Entity\Order;
use App\Repository\UserRepository;
use App\SDK\UberEats\OrderSDK;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/orders/ubereats", name="order_ubereats_")
 */
class UberEatsOrderController extends AbstractController
{
    /**
     * @var OrderSDK
     */
    protected OrderSDK $orderSDK;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(OrderSDK $orderSDK, EntityManagerInterface $entityManager)
    {
        $this->orderSDK = $orderSDK;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/accept/{id}", name="accept", methods={"GET"})
     */
    public function accept(Request $request, Order $order): Response
    {
        $this->orderSDK->acceptOrder($order->getDisplayId());

        $order->setCurrentState('ACCEPTED');

        $this->entityManager->flush($order);

        return $this->redirectToRoute('restaurant_order_index');
    }

    /**
     * @Route("/deny/{id}", name="deny", methods={"GET"})
     */
    public function deny(Request $request, Order $order): Response
    {
        $this->orderSDK->denyOrder($order->getDisplayId());

        $order->setCurrentState('DENIED');

        $this->entityManager->flush($order);

        return $this->redirectToRoute('restaurant_order_index');
    }

    /**
     * @Route("/cancel/{id}", name="cancel", methods={"GET"})
     */
    public function cancel(Request $request, Order $order): Response
    {
        $this->orderSDK->cancelOrder($order->getDisplayId());

        $order->setCurrentState('CANCELED');

        $this->entityManager->flush($order);

        return $this->redirectToRoute('restaurant_order_index');
    }
}
