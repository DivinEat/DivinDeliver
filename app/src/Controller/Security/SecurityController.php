<?php

namespace App\Controller\Security;

use App\Entity\ResetPasswordRequest;
use Exception;
use App\Entity\User;
use App\Form\RegisterType;
use App\Service\MailService;
use App\Form\ResetPasswordType;
use App\Form\ForgotPasswordType;
use App\Service\User\ResetPasswordService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $resetPasswordService;
    private $mailService;

    public function __construct(ResetPasswordService $resetPasswordService, MailService $mailService) 
    {
        $this->resetPasswordService = $resetPasswordService;        
        $this->mailService = $mailService;        
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() && in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            return $this->redirectToRoute('admin_default_index');
        } elseif ($this->getUser()) {
            return $this->redirectToRoute('default_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="app_register", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {
        $user = new User();
       
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/forgot_passsword", name="forgot_password", methods={"GET", "POST"})
     */
    public function forgotPassword(Request $request)
    {
        $user = new User();

        $form = $this->createForm(ForgotPasswordType::class, $user);
        $form->handleRequest($request);

        $data = $form->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            $email = $data['email'];
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);
            $token = $this->resetPasswordService->generateResetPasswordRequest($email);

            $this->mailService->sendResetPasswordMail($email, $token, $user->getId());

            return $this->render('security/password/reset_password_link_sent.html.twig', [
                'email' => $email
            ]);
        }

        return $this->render('security/password/forgot_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset_password", name="reset_password", methods={"GET", "POST"})
     */
    public function reset_password(Request $request)
    {
        $token = $request->get('token');
        $userid = $request->get('userid');
        
        // Checker avec une function du service que les requetes sont pas spamées (e.g. 3 requete à la journée)
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($userid);
        $resetPwdRequest = $em
            ->getRepository(ResetPasswordRequest::class)
            ->findOneBy([
                'requestUser' => $user,
                'token' => $token
            ]);

        if ($resetPwdRequest == null) {
            throw new Exception('Y a un blem avec le token ou le user');
        }

        if (!$this->resetPasswordService->resetPassworsRequestIsValid($resetPwdRequest)) {
            return $this->render('security/password/password_already_reset.html.twig');
        }        

        $form = $this->createForm(ResetPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $resetPwdRequest->setRequestCompleted(true);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/password/reset_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
