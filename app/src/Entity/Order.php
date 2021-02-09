<?php

namespace App\Entity;

use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="string", length=5)
     */
    private $display_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $current_state;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisplayId(): ?string
    {
        return $this->display_id;
    }

    public function setDisplayId(string $display_id): self
    {
        $this->display_id = $display_id;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
