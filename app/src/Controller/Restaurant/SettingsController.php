<?php

namespace App\Controller\Restaurant;

use App\Form\SettingStoreType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/settings", name="settings_")
 * @IsGranted("ROLE_RESTAURATEUR")
 */
class SettingsController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $store = $this->getUser()->getStores()->first();

        $form = $this->createForm(SettingStoreType::class, $store);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Paramètre modifié.');

            return $this->redirectToRoute('restaurant_settings_index');
        }

        return $this->render('restaurant/settings/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
