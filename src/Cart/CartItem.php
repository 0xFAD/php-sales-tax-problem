<?php

namespace SalesTax\Cart;

use SalesTax\Catalog\SellableGoodInterface;
use SalesTax\Tax\Rule\TaxRuleInterface;
use SalesTax\Tax\TaxRuleChain;

/**
 * Class CartItem
 * @package SalesTax\Cart
 */
final class CartItem
{
    protected SellableGoodInterface $sellableGood;

    protected int $quantity;

    protected float $taxRate;

    /**
     * CartItem constructor.
     *
     * @param SellableGoodInterface $sellableGood
     * @param int $quantity
     * @param TaxRuleInterface|null $taxRule
     */
    public function __construct(SellableGoodInterface $sellableGood, int $quantity = 1, ?TaxRuleInterface $taxRule = null)
    {
        $this->sellableGood = $sellableGood;
        $this->quantity = $quantity;
        $this->taxRate = 0.00;

        if (!is_null($taxRule)) {
            $taxRule->apply($this);
        }
    }

    public function getProduct(): SellableGoodInterface
    {
        return $this->sellableGood;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTaxRate(): float
    {
        return $this->taxRate;
    }

    public function setTaxRate(float $taxRate)
    {
        $this->taxRate = $taxRate;
    }

    public function getPrice(): float
    {
        return $this->sellableGood->getPrice() * $this->getQuantity();
    }

    public function getTax(): float
    {
        if ($this->getTaxRate() === 0.00) {
            return 0;
        }

        $tax = $this->sellableGood->getPrice() * ($this->getTaxRate() / 100);
        return (ceil($tax / 0.05) * 0.05) * $this->getQuantity();
    }

    public function getTotalCost(): float
    {
        return $this->getPrice() + $this->getTax();
    }
}
