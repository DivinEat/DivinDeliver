<?php

namespace App\Controller\Restaurant\Order;

use App\Entity\Order;
use App\Entity\Store;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WebhookController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $em)
    {
        $this->client = $client;
        $this->em = $em;
    }

    /**
     * @Route("/webhook/ubereat", name="new-order-ubereat", methods={"POST"})
     */
    public function newOrderUberEat(Request $request)
    {
        $response = $this->client->request(
            'GET',
            $request->get('resource_href')
        );

        $orderData = json_decode($response->getContent(), true);

        $order = new Order();
        $order->setDeliver('ubereat');
        $order->setDisplayId($orderData['_id']);

        $storeRepository = $this->em->getRepository(Store::class);
        $store = $storeRepository->findOneBy(['storeIdFakeUberEat' => $orderData['store_id']]);
        $order->setStore($store);

        $order->setCurrentState($orderData['current_state']);

        $content = [
            'eater' => $orderData['eater'],
            'cart' => $orderData['cart'],
            'payment' => $orderData['payment'],
            'estimated_ready_for_pickup_at' => $orderData['estimated_ready_for_pickup_at']
        ];
        $order->setContent($content);

        $this->em->persist($order);
        $this->em->flush();

        return new Response('Saved new order with id ' . $order->getId());
    }

    /**
     * @Route("/webhook/delieroo", name="new-order-deliveroo", methods={"POST"})
     */
    public function newOrderDelieroo(Request $request)
    {
        $response = $this->client->request(
            'GET',
            $request->get('resource_href')
        );

        $orderData = json_decode($response->getContent(), true);

        $order = new Order();
        $order->setDeliver('deliveroo');
        $order->setDisplayId($orderData['_id']);

        $storeRepository = $this->em->getRepository(Store::class);
        $store = $storeRepository->findOneBy(['storeIdFakeDeliveroo' => $orderData['store_id']]);
        $order->setStore($store);

        $order->setCurrentState($orderData['current_state']);

        $content = [
            'eater' => $orderData['eater'],
            'cart' => $orderData['cart'],
            'payment' => $orderData['payment'],
            'estimated_ready_for_pickup_at' => $orderData['estimated_ready_for_pickup_at']
        ];
        $order->setContent($content);

        $this->em->persist($order);
        $this->em->flush();

        return new Response('Saved new order with id ' . $order->getId());
    }
}
