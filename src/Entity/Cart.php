<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartElement::class, orphanRemoval: true)]
    private $cartElements;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $totalPrice = 0;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'carts')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'integer')]
    private $quantityTotal = 0;

    public function __construct()
    {
        $this->cartElements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, CartElement>
     */
    public function getCartElements(): Collection
    {
        return $this->cartElements;
    }

    public function addCartElement(CartElement $cartElement): self
    {
        if (!$this->cartElements->contains($cartElement)) {
            $this->cartElements[] = $cartElement;
            $cartElement->setCart($this);
        }

        return $this;
    }

    public function removeCartElement(CartElement $cartElement): self
    {
        if ($this->cartElements->removeElement($cartElement)) {
            // set the owning side to null (unless already changed)
            if ($cartElement->getCart() === $this) {
                $cartElement->setCart(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(string $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getQuantityTotal(): ?int
    {
        return $this->quantityTotal;
    }

    public function setQuantityTotal(int $quantityTotal): self
    {
        $this->quantityTotal = $quantityTotal;

        return $this;
    }
}
