<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PasswordSpecialValidator extends ConstraintValidator
{
    private $translator;
    private $security;

    public function __construct(TranslatorInterface $translator, Security $security)
    {
        $this->translator = $translator;
        $this->security = $security;
    }

    public function validate($pwd, Constraint $constraint) 
    {
        if (!$constraint instanceof PasswordSpecial)
            throw new UnexpectedTypeException($constraint, PasswordSpecial::class);

        if (null === $pwd || '' === $pwd)
            return;
        
        $no_special = preg_match('/(?=.*[!@#$%^&*.,:=?;+])/', $pwd) == false;

        if ($no_special)
            $this->context->buildViolation(
                $this->translator->trans(
                    $constraint->message, [], 'validators'
                ))
                ->addViolation();
    }
}