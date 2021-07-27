<?php

namespace App\Validator;

use App\Entity\User;
use App\Repository\ItemRepository;
use App\Repository\CategoryRepository;
use App\Validator\UniqueCategoryTitle;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueItemTitleValidator extends ConstraintValidator
{
    private $translator;
    private $security;
    private $itemRepository;

    public function __construct(TranslatorInterface $translator, Security $security, ItemRepository $itemRepository)
    {
        $this->translator = $translator;
        $this->security = $security;
        $this->itemRepository = $itemRepository;
    }

    public function validate($title, Constraint $constraint) 
    {
        if (!$constraint instanceof UniqueItemTitle)
            throw new UnexpectedTypeException($constraint, UniqueItemTitle::class);

        if (null === $title || '' === $title)
            return;
        

        $user = $this->security->getUser();

        $title_exists = !empty($this->itemRepository->findBy(['title' =>  $title, 'store' => $user->getStores()->first()->getId()]));

        if ($title_exists)
            $this->context->buildViolation(
                $this->translator->trans(
                    $constraint->message, [], 'validators'
                ))
                ->addViolation();
    }
}