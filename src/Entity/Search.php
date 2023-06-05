<?php

namespace App\Entity;

class Search
{

    /**
     * @var point|null
     */
    private $coordinates;

    /**
     * @var array|null
     */
    private $category;

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
}
