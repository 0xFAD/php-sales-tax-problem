<?php

namespace SalesTax\Catalog;

/**
 * Interface SellableGoodInterface
 * @package SalesTax\Catalog
 */
interface SellableGoodInterface
{
    const CATEGORY_MEDICAL = 'MEDICAL';
    const CATEGORY_FOOD = 'FOOD';
    const CATEGORY_BOOK = 'BOOK';
    const CATEGORY_IMPORTED = 'IMPORTED';

    public function getName(): string;
    public function getPrice(): float;
    public function getCategories(): array;
}
