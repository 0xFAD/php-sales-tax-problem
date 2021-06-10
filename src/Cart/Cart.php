<?php

namespace SalesTax\Cart;

use SalesTax\Catalog\SellableGoodInterface;
use SalesTax\Tax\TaxRuleChain;

/**
 * Class Cart
 * @package SalesTax\Cart
 */
final class Cart implements \IteratorAggregate, \ArrayAccess
{
    /**
     * @var CartItem[]
     */
    protected array $items = [];

    protected TaxRuleChain $taxRuleChain;

    /**
     * Cart constructor.
     * @param TaxRuleChain|null $taxRuleChain
     */
    public function __construct(?TaxRuleChain $taxRuleChain = null)
    {
        $this->taxRuleChain = $taxRuleChain ?: new TaxRuleChain();
    }

    /**
     * Add product in the basket
     *
     * @param SellableGoodInterface $good
     * @param int $quantity
     */
    public function addProduct(SellableGoodInterface $good, int $quantity = 1)
    {
        $this->items[] = new CartItem($good, $quantity, $this->taxRuleChain);
    }

    /**
     * TODO
     *
     * @return float
     */
    public function getPrice(): float
    {
        return round(array_reduce($this->items, function(float $sum, CartItem $item) {
            $sum += $item->getPrice();
            return $sum;
        }, 0.00), 2);
    }

    /**
     * TODO
     *
     * @return float
     */
    public function getTax(): float
    {
        return round(array_reduce($this->items, function(float $sum, CartItem $item) {
            $sum += $item->getTax();
            return $sum;
        }, 0.00), 2);
    }

    /**
     * TODO
     *
     * @return float
     */
    public function getTotalCost(): float
    {
        return round(array_reduce($this->items, function(float $sum, CartItem $item) {
            $sum += $item->getTotalCost();
            return $sum;
        }, 0.00), 2);
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset): ?CartItem
    {
        if ($this->offsetExists($offset)) {
            return $this->items[$offset];
        }

        return null;
    }

    /**
     * @param mixed $offset
     * @param SellableGoodInterface $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->addProduct($value);
        }
    }

    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->items[$offset]);
        }
    }
}
