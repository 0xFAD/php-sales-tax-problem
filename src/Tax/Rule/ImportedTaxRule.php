<?php

namespace SalesTax\Tax\Rule;

use SalesTax\Cart\CartItem;
use SalesTax\Catalog\SellableGoodInterface;

/**
 * Class ImportedTaxRule
 * @package SalesTax\Tax\Rule
 */
final class ImportedTaxRule implements TaxRuleInterface
{
    const TAX_RATE = 5;

    public function apply(CartItem $item)
    {
        if (in_array(SellableGoodInterface::CATEGORY_IMPORTED, $item->getProduct()->getCategories())) {
            $currentTaxRate = $item->getTaxRate();
            $item->setTaxRate($currentTaxRate + self::TAX_RATE);
        }
    }
}
