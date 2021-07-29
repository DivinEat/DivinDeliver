<?php

namespace App\Controller\Restaurant\Order;

use App\Entity\Order;
use App\Entity\Store;
use App\MercureHub;
use App\Repository\StoreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WebhookController extends AbstractController
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $em)
    {
        $this->client = $client;
        $this->em = $em;
    }

    /**
     * @Route("/webhook/ubereat/{id}", name="new-order-ubereat", methods={"POST"})
     */
    public function newOrderUberEat(Request $request, HubInterface $hub, Store $store)
    {
        $response = $this->client->request(
            'GET',
            $request->get('resource_href')
        );

        $orderData = json_decode($response->getContent(), true);

        $order = new Order();
        $order->setDeliver('ubereat');
        $order->setDisplayId($orderData['_id']);
        $order->setStore($store);

        $order->setCurrentState($orderData['current_state']);

        $content = [
            'eater' => $orderData['eater'],
            'cart' => $orderData['cart'],
            'payment' => $orderData['payment'],
            'estimated_ready_for_pickup_at' => $orderData['estimated_ready_for_pickup_at'],
        ];
        $order->setContent($content);
        $order->setType($orderData['type']);

        $this->em->persist($order);
        $this->em->flush();

        $update = new Update(
            'new-order-topic-' .  $store->getId(),
            $order->getId(),
            false
        );

        $hub->publish($update);

        return new Response('Saved new order with id ' . $order->getId());
    }

    /**
     * @Route("/webhook/deliveroo/{id}", name="new-order-deliveroo", methods={"POST"})
     */ 
    public function newOrderDeliveroo(Request $request, Store $store)
    {
        $orderData = json_decode($request->getContent(), 1);
        $orderArray = current($orderData['order_events'])['payload']['order'];

        $order = new Order();
        $order->setDeliver('Deliveroo');
        $order->setDisplayId($orderData['order_id']);
        $order->setStore($store);
        $order->setCurrentState('CREATED');

        $content = [
            'eater' => ['first_name' => 'Unknown'],
            'cart' => ['items' => $orderArray['items']],
            'payment' => ['charges' => ['total_fee' => ['formatted_amount' => $orderArray['total_price']['fractional']]]],
            'estimated_ready_for_pickup_at' => $orderArray['pickup_at']
        ];

        $order->setContent($content);
        $order->setType('DELIVERY_BY_DELIVEROO');

        $this->em->persist($order);
        $this->em->flush();

        return new Response('Saved new order with id ' . $order->getId());
    }
}