<?php

namespace App\Controller\Restaurant\Order;

use App\Entity\Order;
use App\Entity\Store;
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
     * @Route("/test", name="new-order-ubereat", methods={"POST"})
     */
    public function test(Request $request, HubInterface $hub)
    {
        $url = $this->generateUrl('restaurant_new-order-ubereat');

        $update = new Update(
            'http://localhost:8082/restaurant/test',
            "[]",
            true
        );

        $hub->publish($update);
    }

    /**
     * @Route("/webhook/ubereat/{storeId}", name="new-order-ubereat", methods={"POST"})
     */
    public function newOrderUberEat(Request $request, HubInterface $hub, string $storeId)
    {
        /** @var StoreRepository $storeRepository */
        $storeRepository = $this->em->getRepository(Store::class);
        $store = $storeRepository->find($storeId);

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

        $url = $this->generateUrl('restaurant_new-order-ubereat');

        $update = new Update(
            'http://localhost:8082/restaurant/webhook/ubereat',
            $order->getId(),
            false
        );

        $hub->publish($update);

        return new Response('Saved new order with id ' . $order->getId());
    }

    /**
     * @Route("/webhook/deliveroo/{$storeId}", name="new-order-deliveroo", methods={"POST"})
     */
    public function newOrderDeliveroo(Request $request, string $storeId)
    {
        /** @var StoreRepository $storeRepository */
        $storeRepository = $this->em->getRepository(Store::class);
        $store = $storeRepository->find($storeId);

        if ($store === null)
            return new Response();

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
        $order->setType($orderData['type']);

        $this->em->persist($order);
        $this->em->flush();

        return new Response('Saved new order with id ' . $order->getId());
    }
}
