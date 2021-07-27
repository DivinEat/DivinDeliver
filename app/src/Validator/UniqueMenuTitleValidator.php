<?php

namespace App\Validator;

use App\Entity\User;
use App\Repository\MenuRepository;
use App\Validator\UniqueEmail;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueMenuTitleValidator extends ConstraintValidator
{
    private $translator;
    private $security;
    private $menuRepository;

    public function __construct(TranslatorInterface $translator, Security $security, MenuRepository $menuRepository)
    {
        $this->translator = $translator;
        $this->security = $security;
        $this->menuRepository = $menuRepository;
    }

    public function validate($title, Constraint $constraint) 
    {
        if (!$constraint instanceof UniqueMenuTitle)
            throw new UnexpectedTypeException($constraint, UniqueMenuTitle::class);

        if (null === $title || '' === $title)
            return;
        

        $user = $this->security->getUser();

        $title_exists = !empty($this->menuRepository->findBy(['title' =>  $title, 'store' => $user->getStores()->first()->getId()]));

        if ($title_exists)
            $this->context->buildViolation(
                $this->translator->trans(
                    $constraint->message, [], 'validators'
                ))
                ->addViolation();
    }
}