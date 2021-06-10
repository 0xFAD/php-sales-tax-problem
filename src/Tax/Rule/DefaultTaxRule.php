<?php

namespace SalesTax\Tax\Rule;

use SalesTax\Cart\CartItem;

/**
 * Class DefaultTaxRule
 * @package SalesTax\Tax\Rule
 */
final class DefaultTaxRule implements TaxRuleInterface
{
    const TAX_RATE = 10;

    public function apply(CartItem $item)
    {
        $item->setTaxRate(self::TAX_RATE);
    }
}
