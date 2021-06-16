<?php

namespace App\Entity;

use App\Entity\Store;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 */
class Order
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
    private $deviver_order_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $deliver;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $current_state;

    /**
     * @ORM\ManyToOne(targetEntity=Store::class, inversedBy="orders")
     */
    private $store;

    /**
     * @ORM\Column(type="json")
     */
    private $content = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeliverOrderId(): ?string
    {
        return $this->deviver_order_id;
    }

    public function setDeliverOrderId(string $deviver_order_id): self
    {
        $this->deviver_order_id = $deviver_order_id;

        return $this;
    }

    public function getDeliver(): ?string
    {
        return $this->deliver;
    }

    public function setDeliver(string $deliver): self
    {
        $this->deliver = $deliver;

        return $this;
    }

    public function getCurrentState(): ?string
    {
        return $this->current_state;
    }

    public function setCurrentState(string $current_state): self
    {
        $this->current_state = $current_state;

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

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(array $content): self
    {
        $this->content = $content;

        return $this;
    }
}
