<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
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
    private ?Point $coordinates;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    private ?User $id_user = null;

    #[ORM\OneToOne(mappedBy: 'address', cascade: ['persist', 'remove'])]
    private ?Shop $shop = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $google_link = null;

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

    /**
	 * @return Point|null
	 */
	public function getCoordinates(): ?Point {
         		return $this->coordinates;
         	}
	
	/**
	 * @param Point|null $coordinates
	 */
	public function setCoordinates(?Point $coordinates): void {
         		$this->coordinates = $coordinates;
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

    public function getGoogleLink(): ?string
    {
        return $this->google_link;
    }

    public function setGoogleLink(?string $google_link): self
    {
        $this->google_link = $google_link;

        return $this;
    }
}
