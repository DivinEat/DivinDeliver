<?php

namespace App\Entity;

use App\Entity\Item;
use App\Entity\Menu;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Order;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\StoreRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=StoreRepository::class)
 * @UniqueEntity("name", message="store.name.unique")
 * @UniqueEntity("slug", message="store.slug.unique")
 */
class Store
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
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="stores", cascade={"persist"})
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="store", cascade={"persist"})
     */
    private $items;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="store", cascade={"persist"})
     */
    private $categories;
    
    /**
     * @ORM\OneToMany(targetEntity=Menu::class, mappedBy="store", cascade={"persist"})
     */
    private $menus;
    
    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="store", cascade={"persist"})
     */
    private $orders;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->orders = new ArrayCollection();
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

    public function getItems(): ?Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setStore($this);
        }

        return $this;
    }
    
    public function getCategories(): ?Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setStore($this);
        }

        return $this;
    }

    public function getMenus(): ?Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->setStore($this);
        }

        return $this;
    }

    public function getOrders(): ?Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setStore($this);
        }

        return $this;
    }
}