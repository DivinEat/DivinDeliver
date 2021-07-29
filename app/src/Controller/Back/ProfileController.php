<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\ProfileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/profile", name="profile_")
 */
class ProfileController extends AbstractController
{

    private $translator;
    private $security;

    public function __construct(UserPasswordEncoderInterface $encoder, TranslatorInterface $translator,  Security $security)
    {
        $this->encoder = $encoder;
        $this->translator = $translator;
        $this->security = $security;
    }

    /**
     * @Route("/", name="index", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $userClone = clone $this->getUser();
        $user = $this->getUser();


        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', $this->translator->trans('user.profile_updated'));

                return $this->redirectToRoute('back_profile_index');
            }
            else {
                $this->getUser()->setEmail($userClone->getEmail());
            }
        }

        return $this->render('back/profile/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
