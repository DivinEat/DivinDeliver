<?php

namespace App\Controller\Front;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller\Front
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default_index", methods={"GET"})
     */
    public function index()
    {
        dump($this->getUser());
        return $this->render('front/default/index.html.twig');
    }

    /**
     * @Route("/custom", name="default_custom", methods={"GET"})
     */
    public function custom()
    {
        return $this->render('front/default/index.html.twig');
    }
}
