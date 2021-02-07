<?php

namespace App\Controller\Front;

use App\Entity\Store;
use App\Form\StoreType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StoreController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/new_store", name="new_store", methods={"GET","POST"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function new(Request $request)
    {
        $store = new Store();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(StoreType::class, $store);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $store->setRestaurateur($this->security->getUser());
            $em->persist($store);
            $em->flush();

            return $this->redirectToRoute('admin_default_index');
        }

        return $this->render('front/store/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}