<?php

namespace App\Entity;

use CrEOF\Spatial\PHP\Types\Geometry\Point;

class Search
{

    /**
     * @var Point|null
     */
    private $coordinates;

    /**
     * @var array|null
     */
    private $category;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $postal_code;

    /**
     * @var string
     */
    private $city;

    /**
     * Get the value of coordinates
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * Set the value of coordinates
     */
    public function setCoordinates($coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * Get the value of category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     */
    public function setCategory($category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of street
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set the value of street
     */
    public function setStreet($street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get the value of postal_code
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * Set the value of postal_code
     */
    public function setPostalCode($postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    /**
     * Get the value of city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     */
    public function setCity($city): self
    {
        $this->city = $city;

        return $this;
    }
}
