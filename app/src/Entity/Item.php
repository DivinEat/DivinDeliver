<?php

namespace App\Entity;

use App\Entity\Category;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ItemRepository;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
{
    use EntityTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceInfo;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPriceInfo(): ?int
    {
        return $this->priceInfo;
    }

    public function setPriceInfo(int $priceInfo): self
    {
        $this->priceInfo = $priceInfo;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    
}
