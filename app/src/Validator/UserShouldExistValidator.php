<?php

namespace App\Validator;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserShouldExistValidator extends ConstraintValidator
{
    private $em;
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }


    public function validate($email, Constraint $constraint) 
    {
        if (!$constraint instanceof UserShouldExist)
            throw new UnexpectedTypeException($constraint, UserShouldExist::class);

        if (null === $email || '' === $email)
            return;

        $repository = $this->em->getRepository(User::class);

        $userExist = $repository->findOneBy(['email' => $email]);

        if (!$userExist)
            $this->context->buildViolation(
                $this->translator->trans(
                    $constraint->message,
                    ['%email%' => $email],
                    'validators'
                ))
                ->addViolation();
    }
}