<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default_index", methods={"GET"})
     */
    public function index()
    {
        return $this->render('back/default/index.html.twig');
    }
}
