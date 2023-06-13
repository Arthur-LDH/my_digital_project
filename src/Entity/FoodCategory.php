<?php

namespace App\Entity;

use App\Repository\FoodCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FoodCategoryRepository::class)]
class FoodCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Shop::class, mappedBy: 'category')]
    private Collection $shops;

    #[ORM\ManyToMany(targetEntity: RestaurantSearch::class, mappedBy: 'category')]
    private Collection $restaurantSearches;

    public function __construct()
    {
        $this->shops = new ArrayCollection();
        $this->restaurantSearches = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
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
     * @return Collection<int, Shop>
     */
    public function getShops(): Collection
    {
        return $this->shops;
    }

    public function addShop(Shop $shop): self
    {
        if (!$this->shops->contains($shop)) {
            $this->shops->add($shop);
            $shop->addCategory($this);
        }

        return $this;
    }

    public function removeShop(Shop $shop): self
    {
        if ($this->shops->removeElement($shop)) {
            $shop->removeCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, RestaurantSearch>
     */
    public function getRestaurantSearches(): Collection
    {
        return $this->restaurantSearches;
    }

    public function addRestaurantSearch(RestaurantSearch $restaurantSearch): self
    {
        if (!$this->restaurantSearches->contains($restaurantSearch)) {
            $this->restaurantSearches->add($restaurantSearch);
            $restaurantSearch->addCategory($this);
        }

        return $this;
    }

    public function removeRestaurantSearch(RestaurantSearch $restaurantSearch): self
    {
        if ($this->restaurantSearches->removeElement($restaurantSearch)) {
            $restaurantSearch->removeCategory($this);
        }

        return $this;
    }
}
