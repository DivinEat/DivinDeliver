<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 */
class Menu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     */
    private $entree;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $main;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     */
    private $dessert;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $drink;

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

    public function getEntree(): ?Product
    {
        return $this->entree;
    }

    public function setEntree(?Product $entree): self
    {
        $this->entree = $entree;

        return $this;
    }

    public function getMain(): ?Product
    {
        return $this->main;
    }

    public function setMain(?Product $main): self
    {
        $this->main = $main;

        return $this;
    }

    public function getDessert(): ?Product
    {
        return $this->dessert;
    }

    public function setDessert(?Product $dessert): self
    {
        $this->dessert = $dessert;

        return $this;
    }

    public function getDrink(): ?Product
    {
        return $this->drink;
    }

    public function setDrink(?Product $drink): self
    {
        $this->drink = $drink;

        return $this;
    }
}
