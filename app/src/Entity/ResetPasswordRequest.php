<?php

namespace App\Entity;

use DateTime;
use App\Entity\User;
use DateTimeInterface;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ResetPasswordRequestRepository;

/**
 * @ORM\Entity(repositoryClass=ResetPasswordRequestRepository::class)
 */
class ResetPasswordRequest
{

    use EntityTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="resetPwdRequests")
     */
    private $requestUser;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $requestDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $requestCompleted = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestUser(): ?User
    {
        return $this->requestUser;
    }

    public function setRequestUser(User $requestUser): self
    {
        $this->requestUser = $requestUser;

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

    public function getRequestDate(): ?\DateTimeInterface
    {
        return $this->requestDate;
    }

    public function setRequestDate(\DateTimeInterface $requestDate): self
    {
        $this->requestDate = $requestDate;

        return $this;
    }

    public function getRequestCompleted(): ?bool
    {
        return $this->requestCompleted;
    }

    public function setRequestCompleted(bool $requestCompleted): self
    {
        $this->requestCompleted = $requestCompleted;

        return $this;
    }
}
