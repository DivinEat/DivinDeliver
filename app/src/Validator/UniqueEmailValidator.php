<?php

namespace App\Validator;

use App\Entity\User;
use App\Validator\UniqueEmail;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueEmailValidator extends ConstraintValidator
{
    private $translator;
    private $security;
    private $userRepository;

    public function __construct(TranslatorInterface $translator, Security $security, UserRepository $userRepository)
    {
        $this->translator = $translator;
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    public function validate($email, Constraint $constraint) 
    {
        if (!$constraint instanceof UniqueEmail)
            throw new UnexpectedTypeException($constraint, UniqueEmail::class);

        if (null === $email || '' === $email)
            return;
        

        $users = $this->userRepository->findBy(array('email' => $email));

        $email_exists = !empty($users) && !( sizeof($users) == 1 && $users[0]->getId() == $this->context->getRoot()->getData()->getId() );
        
        if ($email_exists)
            $this->context->buildViolation(
                $this->translator->trans(
                    $constraint->message, [], 'validators'
                ))
                ->addViolation();
    }
}