<?php


namespace App\Controller\Back;


use App\Entity\AuthApp;
use App\Form\AuthAppType;
use App\Repository\AuthAppRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/link", name="link_")
 */
class LinkAppController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET", "POST"})
     */
    public function index(AuthAppRepository $authAppRepository, Request $request)
    {
        $authApp = new AuthApp();
        $form = $this->createForm(AuthAppType::class, $authApp);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($authApp);
            $em->flush();
        }

        return $this->render('back/link/index.html.twig', [
            'form' => $form->createView(),
            'apps' => $authAppRepository->findAll()
        ]);
    }

    /**
     * @Route("/sync/{id}", name="sync", methods={"GET"})
     */
    public function sync(AuthApp $authApp)
    {
        dd($authApp);
    }


    /**
     * @Route ("/redirect/{app}/", name="redirect", methods={"GET"})
     */
    public function redirectUri(Request $request)
    {
        dd($request);
    }
}