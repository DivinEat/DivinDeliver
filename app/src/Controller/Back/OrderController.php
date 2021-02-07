<?php

namespace App\Controller\Back;

use App\SDK\UberEats\OrderSDK;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/order", name="order_")
 */
class OrderController extends AbstractController
{
    /**
     * @var OrderSDK
     */
    protected OrderSDK $orderSDK;

    public function __construct(OrderSDK $orderSDK)
    {
        $this->orderSDK = $orderSDK;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        dd($this->orderSDK->getActiveCreatedOrders('2b04ce24-1aad-4ea7-91ef-844de630a923'));
//        return $this->render('back/order/index.html.twig');
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