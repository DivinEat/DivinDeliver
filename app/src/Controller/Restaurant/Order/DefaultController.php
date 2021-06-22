<?php


namespace App\Controller\Restaurant\Order;


use App\Repository\UserRepository;
use App\SDK\Deliveroo\OrderSDK as DeliverooOrderSDK;
use App\SDK\UberEats\OrderSDK;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/orders", name="order_")
 */
class DefaultController extends AbstractController
{
    /**
     * @var OrderSDK $uEOrderSDK
     */
    protected OrderSDK $uEOrderSDK;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var DeliverooOrderSDK
     */
    private DeliverooOrderSDK $dOrderSDK;

    public function __construct(OrderSDK $uEOrderSDK, DeliverooOrderSDK $dOrderSDK)
    {
        $this->uEOrderSDK = $uEOrderSDK;
        $this->dOrderSDK = $dOrderSDK;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $choice = $request->query->get('choice') ?? "accepted";
        $storeID = $this->getUser()->getStores()->first()->getStoreIdFakeUberEat();

        if ("accepted" == $choice) {
            $orders = array_merge(
                $this->uEOrderSDK->getActiveCreatedOrders($storeID),
                $this->dOrderSDK->getActiveCreatedOrders($storeID)
            );
        } else {
            $orders = array_merge(
                $this->uEOrderSDK->getCancelOrders($storeID),
                $this->dOrderSDK->getCancelOrders($storeID)
            );
        }

        return $this->render('restaurant/order/index.html.twig', ['orders' => $orders, 'choice' => $choice]);
    }

    /**
     * @Route("/show/{deliver}/{orderId}", name="show", methods={"GET"})
     */
    public function show(String $orderId, String $deliver)
    {
        switch ($deliver) {
            case "ubereat":
                $order = $this->uEOrderSDK->getOrderDetails($orderId);
                break;
            case "deliveroo":
                $order = $this->dOrderSDK->getOrderDetails($orderId);
                break;
            default:
                break;
        }

        return $this->render('restaurant/order/show.html.twig', [
            'order' => $order
        ]);
    }
}
