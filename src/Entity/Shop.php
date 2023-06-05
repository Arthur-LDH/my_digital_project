<?php

namespace App\Entity;

use App\Repository\ShopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShopRepository::class)]
class Shop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column]
    private ?bool $delivery = null;

    #[ORM\Column]
    private ?bool $take_away = null;

    #[ORM\Column(nullable: true)]
    private ?int $avg_price = null;

    #[ORM\OneToOne(inversedBy: 'shop', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $address = null;

    #[ORM\ManyToMany(targetEntity: FoodCategory::class, inversedBy: 'shops')]
    private Collection $category;

    private ?string $distance;

    public function __construct()
    {
        $this->category = new ArrayCollection();
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

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function isDelivery(): ?bool
    {
        return $this->delivery;
    }

    public function setDelivery(bool $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function isTakeAway(): ?bool
    {
        return $this->take_away;
    }

    public function setTakeAway(bool $take_away): self
    {
        $this->take_away = $take_away;

        return $this;
    }

    public function getAvgPrice(): ?int
    {
        return $this->avg_price;
    }

    public function setAvgPrice(?int $avg_price): self
    {
        $this->avg_price = $avg_price;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, FoodCategory>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(FoodCategory $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(FoodCategory $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * Get the value of distance
     *
     * @return ?string
     */
    public function getDistance(): ?string
    {
        return $this->distance;
    }

    /**
     * Set the value of distance
     *
     * @param ?string $distance
     *
     * @return self
     */
    public function setDistance(?string $distance): self
    {
        $this->distance = $distance;

        return $this;
    }
}
