<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $houseNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column(length: 255)]
    private ?string $postal_code = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(type: 'point')]
    private $coordinates = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    private ?User $id_user = null;

    #[ORM\OneToOne(mappedBy: 'address', cascade: ['persist', 'remove'])]
    private ?Shop $shop = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    public function __toString()
    {
        if ($this->getHouseNumber() === null) {
            return $this->getStreet() . ', ' . $this->getPostalCode() . ' ' . $this->getCity();
        }
        return $this->getHouseNumber() . ' ' . $this->getStreet() . ', ' . $this->getPostalCode() . ' ' . $this->getCity();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(?string $houseNumber): self
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCoordinates()
    {
        return $this->coordinates;
    }

    public function setCoordinates($coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(Shop $shop): self
    {
        // set the owning side of the relation if necessary
        if ($shop->getAddress() !== $this) {
            $shop->setAddress($this);
        }

        $this->shop = $shop;

        return $this;
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
}
