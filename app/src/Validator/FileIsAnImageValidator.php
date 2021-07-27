<?php

namespace App\Validator;

use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class FileIsAnImageValidator extends ConstraintValidator
{
    private $translator;
    private $security;

    public function __construct(TranslatorInterface $translator, Security $security)
    {
        $this->translator = $translator;
        $this->security = $security;
    }

    public function validate($file, Constraint $constraint) 
    {
        if (!$constraint instanceof FileIsAnImage)
            throw new UnexpectedTypeException($constraint, FileIsAnImage::class);

        if (null === $file)
            return;

        $not_valid = getimagesize($file) == false;

        if ($not_valid)
            $this->context->buildViolation(
                $this->translator->trans(
                    $constraint->message, [], 'validators'
                ))
                ->addViolation();
    }
}