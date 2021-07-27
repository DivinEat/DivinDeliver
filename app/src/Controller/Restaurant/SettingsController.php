<?php

namespace App\Controller\Restaurant;

use App\Form\SettingStoreType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/settings", name="settings_")
 * @IsGranted("ROLE_RESTAURATEUR")
 */
class SettingsController extends AbstractController
{

    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

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

            $this->addFlash('success', $this->translator->trans('settings.updated'));

            return $this->redirectToRoute('restaurant_settings_index');
        }

        return $this->render('restaurant/settings/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
