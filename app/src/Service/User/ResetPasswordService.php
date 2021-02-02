<?php

namespace App\Service\User;

use Exception;
use App\Entity\User;
use App\Entity\ResetPasswordRequest;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class ResetPasswordService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Generate and store a token in ResetPasswordRequest table
     * @return String $token le token généré
     */
    public function generateResetPasswordRequest(String $email): String
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user)
            throw new Exception("The user does not exists");

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

        return $diff_minutes < 1;
    }
}