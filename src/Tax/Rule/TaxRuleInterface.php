<?php

namespace SalesTax\Tax\Rule;

use SalesTax\Cart\CartItem;

/**
 * Interface TaxRuleInterface
 * @package SalesTax\Tax
 */
interface TaxRuleInterface
{
    public function apply(CartItem $item);
}
