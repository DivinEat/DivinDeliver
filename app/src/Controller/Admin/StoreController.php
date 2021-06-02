<?php

namespace App\Controller\Admin;

use Exception;
use App\Entity\Store;
use App\Form\StoreEditType;
use App\Repository\StoreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/store", name="store_")
 * @IsGranted("ROLE_ADMIN")
 */
class StoreController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(StoreRepository $storeRepository): Response
    {
        return $this->render('admin/store/index.html.twig', [
            'stores' => $storeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     */
    public function show(Store $store): Response
    {
        return $this->render('admin/store/show.html.twig', [
            'store' => $store,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Store $store): Response
    {
        $form = $this->createForm(StoreEditType::class, $store);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_store_edit', [
                'id' => $store->getId()
            ]);
        }

        return $this->render('admin/store/edit.html.twig', [
            'form' => $form->createView(),
            'store' => $store
        ]);
    }

    /**
     * @Route("/delete/{id}/{token}", name="delete", methods={"GET"})
     */
    public function delete(Store $store, $token)
    {
        if (!$this->isCsrfTokenValid('delete_store' . $store->getName(), $token)) {
            throw new Exception('Invalid CSRF Token');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($store);
        $em->flush();

        return $this->redirectToRoute('admin_store_index');
    }
}