<?php

namespace App\Controller\Back;

use Exception;
use App\Entity\User;
use App\Form\UserType;
use App\Service\MailService;
use App\Service\User\UserService;
use App\Repository\UserRepository;
use App\Service\User\ResetPasswordService;
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
    private $resetPasswordService;
    private $mailService;
    
    public function __construct(UserService $userService, ResetPasswordService $resetPasswordService, MailService $mailService)
    {
        $this->userService = $userService;
        $this->resetPasswordService = $resetPasswordService;        
        $this->mailService = $mailService;     
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('back/user/index.html.twig', [
            'users' => $userRepository->getUsersByUser($this->getUser()),
        ]);
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function show(User $user): Response
    {
        if ($this->getUser()->getStores()->first()->getId() !== $user->getStore()->getId())
            throw $this->createAccessDeniedException();

        return $this->render('back/user/show.html.twig', [
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

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('green', 'Utilisateur ajouté.');

            $token = $this->resetPasswordService->generateResetPasswordRequest($user->getEmail());
            $this->mailService->sendNewUserMail($user->getEmail(), $token, $user->getId());

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('back/user/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @IsGranted("ROLE_RESTAURATEUR")
     */
    public function edit(Request $request, User $user): Response
    {
        if ($this->getUser()->getStores()->first()->getId() !== $user->getStore()->getId())
            throw $this->createAccessDeniedException();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_user_edit', [
                'id' => $user->getId()
            ]);
        }

        return $this->render('back/user/edit.html.twig', [
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
        if ($this->getUser()->getStores()->first()->getId() !== $user->getStore()->getId())
            throw $this->createAccessDeniedException();

        if (!$this->isCsrfTokenValid('delete_user' . $user->getLastname(), $token)) {
            throw new Exception('Invalid CSRF Token');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('admin_user_index');
    }
}