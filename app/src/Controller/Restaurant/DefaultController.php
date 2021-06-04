<?php

namespace App\Controller\Restaurant;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default_index", methods={"GET"})
     */
    public function index()
    {
        return $this->render('restaurant/default/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
