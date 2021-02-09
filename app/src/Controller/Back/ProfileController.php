<?php

namespace App\Controller\Back;

use App\Form\ProfileType;
use App\Form\EditPasswordType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/profile", name="profile_")
 */
class ProfileController extends AbstractController
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;        
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('back/profile/index.html.twig');
    }

    /**
     * @Route("/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_profile_index');
        }

        return $this->render('back/profile/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/password", name="edit-password", methods={"GET","POST"})
     */
    public function editPassword(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

    	$form = $this->createForm(EditPasswordType::class, $user);

    	$form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $request->request->get('edit_password')['oldPassword'];

            if($this->encoder->isPasswordValid($user, $oldPassword)) {
                $newEncodedPassword = $this->encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($newEncodedPassword);
    
                $em->persist($user);
                $em->flush();
    
                return $this->redirectToRoute('admin_profile_index');
            } else {
                $form->addError(new FormError("Ancien mot de passe incorrect"));
            }
        }

    	return $this->render('back/profile/editPassword.html.twig', array(
    		'form' => $form->createView(),
    	));
    }
}