<?php

namespace App\Service\User;

use DateTime;
use Exception;
use App\Entity\User;
use PhpParser\Internal\Differ;
use App\Entity\AccountValidation;
use App\Repository\UserRepository;
use App\Entity\ResetPasswordRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccountService
{
    private $em;
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * Generate and store a token in ResetPasswordRequest table
     * @return String $token le token généré
     */
    public function generateResetPasswordRequest(User $user): String
    {
        if (!$user)
            throw new Exception("The user must not be null");

        $token = openssl_random_pseudo_bytes(16);
        $token = bin2hex($token);

        $request = new ResetPasswordRequest();
        $request->setToken($token);
        $request->setRequestUser($user);
        $request->setRequestDate(new DateTime('NOW'));

        $this->em->persist($request);
        $this->em->flush();

        return $token;
    }

    /**
     * Generate and store a token in AccountValidation table
     * @return String $token le token généré
     */
    public function generateAccountValidation(User $user): String
    {
        if (!$user)
            throw new Exception("The user must not be null");

        $token = openssl_random_pseudo_bytes(16);
        $token = bin2hex($token);

        $validation = new AccountValidation();
        $validation->setToken($token);
        $validation->setAccountUser($user);
        $validation->setAccountCreationDate(new DateTime('NOW'));

        $this->em->persist($validation);
        $this->em->flush();

        return $token;
    }

    /**
     * Check if the request has already been used or the link expired
     *      - if $requestCompleted == true
     *      - or request was created more than 60 minutes ago
     * @return bool request is valid or not
     */
    public function resetPassworsRequestIsValid(ResetPasswordRequest $resetPwdRequest): bool
    {
        if ($resetPwdRequest->getRequestCompleted()) return false;

        $now = new DateTime('NOW');
        $requestDate = $resetPwdRequest->getRequestDate();
        $diff_minutes = $requestDate->diff($now)->format('%i');

        return $diff_minutes < 60;
    }

    /**
     * Check if the account is already valid or the link expired
     *      - if $requestCompleted == true
     *      - or request was created more than 60 minutes ago //todo : configurer
     * @return bool request is valid or not
     */
    public function accountValidationIsValid(AccountValidation $accountValidation): array
    {
        $user = $accountValidation->getAccountUser();

        if ($user->isValid())
            return [
                'isValid' => false,
                'code' => 'account_already_valid',
                'message' => $this->translator->trans('account.validation.account_already_valid')
            ];

        $now = new DateTime('NOW');
        $accountCreationDate = $accountValidation->getAccountCreationDate();
        $diff_days = $accountCreationDate->diff($now)->format('%d');

        if ($diff_days > 7)
            return [
                'isValid' => false,
                'code' => 'token_expired',
                'message' => $this->translator->trans('account.validation.token_expired')
            ];

        return ['isValid' => true];
    }
}
