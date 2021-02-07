<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class RestaurateurHasNoStoreValidator extends ConstraintValidator
{
    private $translator;
    private $security;

    public function __construct(TranslatorInterface $translator, Security $security)
    {
        $this->translator = $translator;
        $this->security = $security;
    }

    public function validate($name, Constraint $constraint) 
    {
        if (!$constraint instanceof RestaurateurHasNoStore)
            throw new UnexpectedTypeException($constraint, RestaurateurHasNoStore::class);

        if (null === $name || '' === $name)
            return;

        $user = $this->security->getUser();  

        $storeExist = !$user->getStores()->isEmpty();

        if ($storeExist)
            $this->context->buildViolation(
                $this->translator->trans(
                    $constraint->message, [], 'validators'
                ))
                ->addViolation();
    }
}