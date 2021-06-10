<?php

namespace SalesTax\Catalog;

/**
 * Class Product
 * @package SalesTax\Catalog
 */
class Product implements SellableGoodInterface
{
    protected string $name;

    protected float $price;

    protected array $categories;

    /**
     * Product constructor.
     *
     * @param string $name
     * @param float $price
     * @param array $categories
     */
    public function __construct(string $name, float $price, array $categories = [])
    {
        $this->name = $name;
        $this->price = $price;
        $this->categories = $categories;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }
}
