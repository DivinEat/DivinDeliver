<?php

namespace App\Controller\Restaurant;

use Exception;
use App\Entity\User;
use App\Form\UserType;
use App\Service\MailService;
use App\Service\User\UserService;
use App\Repository\UserRepository;
use App\Service\User\AccountService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/user", name="user_")
 * @IsGranted("ROLE_RESTAURATEUR")
 */
class UserController extends AbstractController
{
    private $userService;
    private $accountService;
    private $mailService;

    public function __construct(UserService $userService, AccountService $accountService, MailService $mailService)
    {
        $this->userService = $userService;
        $this->accountService = $accountService;
        $this->mailService = $mailService;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('restaurant/user/index.html.twig', [
            'users' => $userRepository->getUsersByUser($this->getUser()),
        ]);
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function show(User $user): Response
    {
        if (
            !$user->getStores()->first()
            || $this->getUser()->getStores()->first()->getId() !== $user->getStores()->first()->getId()
        )
            throw $this->createAccessDeniedException();

        return $this->render('restaurant/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function new(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(random_bytes(20));
            $user->addStore($this->getUser()->getStores()->first());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Utilisateur ajouté.');

            $token = $this->accountService->generateAccountValidation($user);
            $this->mailService->sendAccountValidationMail($user->getEmail(), $token, $user->getId());

            return $this->redirectToRoute('restaurant_user_index');
        }

        return $this->render('restaurant/user/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function edit(Request $request, User $user): Response
    {
        if (
            !$user->getStores()->first()
            || $this->getUser()->getStores()->first()->getId() !== $user->getStores()->first()->getId()
        )
            throw $this->createAccessDeniedException();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Utilisateur modifié.');

            return $this->redirectToRoute('restaurant_user_edit', [
                'id' => $user->getId()
            ]);
        }

        return $this->render('restaurant/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/delete/{id}/{token}", name="delete", methods={"GET"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function delete(User $user, $token)
    {
        if (
            !$user->getStores()->first()
            || $this->getUser()->getStores()->first()->getId() !== $user->getStores()->first()->getId()
        )
            throw $this->createAccessDeniedException();

        if (!$this->isCsrfTokenValid('delete_user' . $user->getId(), $token)) {
            throw new Exception('Invalid CSRF Token');
        }

        $this->addFlash('danger', 'Utilisateur supprimé.');

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('restaurant_user_index');
    }
}
