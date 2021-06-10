<?php

namespace SalesTaxTest;

use PHPUnit\Framework\TestCase;
use SalesTax\Cart\Cart;
use SalesTax\Cart\CartItem;
use SalesTax\Catalog\Product;
use SalesTax\Catalog\SellableGoodInterface;
use SalesTax\Tax\Rule\DefaultTaxRule;
use SalesTax\Tax\Rule\ImportedTaxRule;
use SalesTax\Tax\Rule\NoTaxRule;
use SalesTax\Tax\TaxRuleChain;

/**
 * Class TaxTest
 * @package SalesTaxTest
 */
final class TaxTest extends TestCase
{
    /**
     * Test default tax rule (10%)
     */
    public function testDefaultTaxRule()
    {
        $product = new Product('Test product', 100);
        $cartItem = new CartItem($product, 1, new DefaultTaxRule());

        $this->assertEquals(110, $cartItem->getTotalCost());
        $this->assertEquals(10, $cartItem->getTax());
        $this->assertEquals(10, $cartItem->getTaxRate());
    }

    /**
     * Test no tax rule (food, medicinals and books)
     */
    public function testNoTaxRule()
    {
        $product = new Product('Test product', 9.99);
        $cartItem = new CartItem($product, 1, new NoTaxRule());

        $this->assertEquals(9.99, $cartItem->getTotalCost());
        $this->assertEquals(0, $cartItem->getTax());
        $this->assertEquals(0, $cartItem->getTaxRate());
    }

    /**
     * Test imported tax rule with and without default rule applied
     */
    public function testImportedTaxRule()
    {
        $taxChain = new TaxRuleChain([
            new DefaultTaxRule(),
            new NoTaxRule(),
            new ImportedTaxRule()
        ]);

        $productWithDefaultTax = new Product('Test product 1', 20, [SellableGoodInterface::CATEGORY_IMPORTED]);
        $cartItemWithDefaultTax = new CartItem($productWithDefaultTax, 2, $taxChain);

        $this->assertEquals(46, $cartItemWithDefaultTax->getTotalCost());
        $this->assertEquals(6, $cartItemWithDefaultTax->getTax());
        $this->assertEquals(15, $cartItemWithDefaultTax->getTaxRate());

        $productWithoutDefaultTax = new Product('Test product 2', 9.89, [SellableGoodInterface::CATEGORY_IMPORTED, SellableGoodInterface::CATEGORY_FOOD]);
        $cartItemWithoutDefaultTax = new CartItem($productWithoutDefaultTax, 5, $taxChain);

        $this->assertEquals(51.95, $cartItemWithoutDefaultTax->getTotalCost());
        $this->assertEquals(2.5, $cartItemWithoutDefaultTax->getTax());
        $this->assertEquals(5, $cartItemWithoutDefaultTax->getTaxRate());
    }
}
