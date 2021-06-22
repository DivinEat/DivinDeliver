<?php

namespace App\Controller\Restaurant\Order;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    /**
     * @Route("/new-order-notification", name="new-order-notification", methods={"POST"})
     */
    public function newOrderNotification(Request $request)
    {
    }
}
