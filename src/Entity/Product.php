<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[Vich\Uploadable]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'text')]
    private $description;

    // #[ORM\Column(type: 'integer')]
    #[Assert\PositiveOrZero(message:"Le stock doit être un nombre positif ou 0")]
    #[ORM\Column(type:"integer", columnDefinition:"INT CHECK (stock >= 0)")]
    private $stock;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $price;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'products')]
    private $categories;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: CartElement::class, orphanRemoval: true)]
    private $cartElements;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->cartElements = new ArrayCollection();
        $this->updatedAt = new \DateTime();
        $this->comments = new ArrayCollection();
    }

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
    
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     */
    #[Vich\UploadableField(mapping: 'product_image', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string')]
    private ?string $imageName = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Comment::class)]
    private $comments;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addProduct($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeProduct($this);
        }

        return $this;
    }

     public function __toString()
    {
        return $this->name;
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
             $cartElement->setProduct($this);
         }

         return $this;
     }

     public function removeCartElement(CartElement $cartElement): self
     {
         if ($this->cartElements->removeElement($cartElement)) {
             // set the owning side to null (unless already changed)
             if ($cartElement->getProduct() === $this) {
                 $cartElement->setProduct(null);
             }
         }

         return $this;
     }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        if (null !== $imageFile) {
            $this->imageFile = $imageFile;
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setProduct($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProduct() === $this) {
                $comment->setProduct(null);
            }
        }

        return $this;
    }

    public function getRateAvg():float
    {
        $totalRates = count($this->getComments());
        $rates = 0;
        $rateAvg = 0;

        foreach($this->getComments() as $com){
            $rates+= $com->getRate();
        }
        if($totalRates > 0){
            $rateAvg = $rates/$totalRates;
        }

        return $rateAvg;
    }
}
