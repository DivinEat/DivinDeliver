<?php

namespace App\Entity;

use App\Entity\Store;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 * @Vich\Uploadable()
 */
class Menu
{

    use EntityTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class)
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity=Store::class, inversedBy="menus")
     */
    private $store;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cover;

    /**
     * @Vich\UploadableField(mapping="cover_item", fileNameProperty="cover")
     * @var File
     */
    private $imageFile;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->updatedAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }
    
    public function setStore(Store $store): self
    {
        $this->store = $store;

        return $this;
    } 

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }
}
