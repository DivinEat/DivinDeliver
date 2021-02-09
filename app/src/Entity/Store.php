<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\StoreRepository;
use Gedmo\Mapping\Annotation as Gedmo;
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
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="stores")
     */
    private $restaurateur;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="store")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $storeIdFakeUberEat;

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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addStore($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeStore($this);
        }
        return $this;
    }

    public function getStoreIdFakeUberEat(): ?String
    {
        return $this->storeIdFakeUberEat;
    }

    public function setStoreIdFakeUberEat(String $storeIdFakeUberEat): self
    {
        $this->storeIdFakeUberEat = $storeIdFakeUberEat;

        return $this;
    }
}
