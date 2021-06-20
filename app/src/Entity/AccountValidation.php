<?php

namespace App\Entity;

use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AccountValidationRepository;

/**
 * @ORM\Entity(repositoryClass=AccountValidationRepository::class)
 */
class AccountValidation
{
    use EntityTrait;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="token", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $accountUser;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $accountCreationDate;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $accountValidationDate;

    public function getAccountUser(): ?User
    {
        return $this->accountUser;
    }

    public function setAccountUser(User $accountUser): self
    {
        $this->accountUser = $accountUser;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getAccountCreationDate(): ?\DateTimeInterface
    {
        return $this->accountCreationDate;
    }

    public function setAccountCreationDate(\DateTimeInterface $accountCreationDate): self
    {
        $this->accountCreationDate = $accountCreationDate;

        return $this;
    }

    public function getAccountValidationDate(): ?\DateTimeInterface
    {
        return $this->accountValidationDate;
    }

    public function setAccountValidationDate(?\DateTimeInterface $accountValidationDate): self
    {
        $this->accountValidationDate = $accountValidationDate;

        return $this;
    }
}
