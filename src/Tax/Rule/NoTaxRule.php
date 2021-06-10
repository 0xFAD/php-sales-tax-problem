<?php

namespace SalesTax\Tax\Rule;

use SalesTax\Cart\CartItem;
use SalesTax\Catalog\SellableGoodInterface;

/**
 * Class NoTaxRule
 * @package SalesTax\Tax\Rule
 */
final class NoTaxRule implements TaxRuleInterface
{
    public function apply(CartItem $item)
    {
        $categories = [SellableGoodInterface::CATEGORY_FOOD, SellableGoodInterface::CATEGORY_MEDICAL, SellableGoodInterface::CATEGORY_BOOK];
        if ($this->atLeastOne($item->getProduct()->getCategories(), $categories)) {
            $item->setTaxRate(0.00);
        }
    }

    protected function atLeastOne(array $keys, array $values): bool
    {
        $atLeastOne = array_map(function ($hk) use($values) {
            return in_array($hk, $values);
        }, $keys);

        return array_sum($atLeastOne) > 0;
    }
}
