<?php


namespace App\Controller\Admin;


use App\Repository\UserRepository;
use App\Repository\StoreRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default_index", methods={"GET"})
     */
    public function index(StoreRepository $storeRepository, UserRepository $userRepository)
    {
        return $this->render('admin/default/index.html.twig', [
            'user' => $this->getUser(),
            'stores' => count($storeRepository->findAll()),
            'users' => count($userRepository->findAll())
        ]);
    }
}
