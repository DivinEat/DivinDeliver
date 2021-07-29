<?php

namespace App\Controller\Restaurant\Order;

use App\Entity\Order;
use App\Repository\UserRepository;
use \App\SDK\Deliveroo\OrderSDK;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/orders/deliveroo", name="order_deliveroo_")
 */
class DeliverooOrderController extends AbstractController
{
    /**
     * @var OrderSDK
     */
    protected OrderSDK $orderSDK;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

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
        $this->orderSDK->acceptOrder($request->get('id'));

        $order->setCurrentState('ACCEPTED');

        $this->entityManager->flush($order);

        return $this->redirectToRoute('restaurant_order_index');
    }

    /**
     * @Route("/deny/{id}", name="deny", methods={"GET"})
     */
    public function deny(Request $request, Order $order): Response
    {
        $this->orderSDK->denyOrder($request->get('id'));

        $order->setCurrentState('DENIED');

        $this->entityManager->flush($order);

        return $this->redirectToRoute('restaurant_order_index');
    }

    /**
     * @Route("/cancel/{id}", name="cancel", methods={"GET"})
     */
    public function cancel(Request $request, Order $order): Response
    {
        $this->orderSDK->cancelOrder($request->get('id'));

        $order->setCurrentState('CANCELED');

        $this->entityManager->flush($order);

        return $this->redirectToRoute('restaurant_order_index');
    }
}
