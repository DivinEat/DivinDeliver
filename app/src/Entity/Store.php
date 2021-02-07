<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\StoreRepository;
use App\Validator\RestaurateurHasNoStore;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=StoreRepository::class)
 * @UniqueEntity("name", message="store.name.unique")
 * @UniqueEntity("slug", message="store.slug.unique")
 */
class Store
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @RestaurateurHasNoStore
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="stores")
     */
    private $restaurateur;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getRestaurateur(): ?User
    {
        return $this->restaurateur;
    }

    public function setRestaurateur(User $restaurateur): self
    {
        $this->restaurateur = $restaurateur;

        return $this;
    }
}
