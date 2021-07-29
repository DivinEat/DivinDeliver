<?php

namespace App\Validator;

use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Validator\UniqueCategoryTitle;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueCategoryTitleValidator extends ConstraintValidator
{
    private $translator;
    private $security;
    private $categoryRepository;

    public function __construct(TranslatorInterface $translator, Security $security, CategoryRepository $categoryRepository)
    {
        $this->translator = $translator;
        $this->security = $security;
        $this->categoryRepository = $categoryRepository;
    }

    public function validate($title, Constraint $constraint) 
    {
        if (!$constraint instanceof UniqueCategoryTitle)
            throw new UnexpectedTypeException($constraint, UniqueCategoryTitle::class);

        if (null === $title || '' === $title)
            return;
        

        $user = $this->security->getUser();
        $categories = $this->categoryRepository->findBy(['title' =>  $title, 'store' => $user->getStores()->first()->getId()]);

        $title_exists = !empty($categories) && !( sizeof($categories) == 1 && $categories[0]->getId() == $this->context->getRoot()->getData()->getId() );

        if ($title_exists)
            $this->context->buildViolation(
                $this->translator->trans(
                    $constraint->message, [], 'validators'
                ))
                ->addViolation();
    }
}