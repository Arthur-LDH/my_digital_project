<?php

namespace App\Entity;

use App\Repository\RestaurantSearchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestaurantSearchRepository::class)]
class RestaurantSearch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $user_address = null;

    #[ORM\Column(length: 255)]
    private ?string $user_cp = null;

    #[ORM\Column(length: 255)]
    private ?string $user_city = null;

    #[ORM\Column(type: 'point')]
    private $user_coordinates = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'restaurantSearches')]
    private ?User $user = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $results = [];

    #[ORM\ManyToMany(targetEntity: FoodCategory::class, inversedBy: 'restaurantSearches')]
    private Collection $category;

    public function __construct()
    {
        $this->category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserAddress(): ?string
    {
        return $this->user_address;
    }

    public function setUserAddress(string $user_address): self
    {
        $this->user_address = $user_address;

        return $this;
    }

    public function getUserCp(): ?string
    {
        return $this->user_cp;
    }

    public function setUserCp(string $user_cp): self
    {
        $this->user_cp = $user_cp;

        return $this;
    }

    public function getUserCity(): ?string
    {
        return $this->user_city;
    }

    public function setUserCity(string $user_city): self
    {
        $this->user_city = $user_city;

        return $this;
    }

    public function getUserCoordinates()
    {
        return $this->user_coordinates;
    }

    public function setUserCoordinates($user_coordinates): self
    {
        $this->user_coordinates = $user_coordinates;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

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

    public function getResults(): array
    {
        return $this->results;
    }

    public function setResults(array $results): self
    {
        $this->results = $results;

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
}
