<?php

namespace SalesTaxTest;

use PHPUnit\Framework\TestCase;
use SalesTax\Cart\Cart;
use SalesTax\Catalog\Product;
use SalesTax\Catalog\SellableGoodInterface;
use SalesTax\Tax\Rule\DefaultTaxRule;
use SalesTax\Tax\Rule\ImportedTaxRule;
use SalesTax\Tax\Rule\NoTaxRule;
use SalesTax\Tax\TaxRuleChain;

/**
 * Class CartTest
 * @package SalesTaxTest
 */
final class CartTest extends TestCase
{
    /**
     * Build a mock-up cart with all tax rules
     *
     * @return Cart
     */
    protected function getCart(): Cart
    {
        $taxRules = new TaxRuleChain();
        $taxRules->addRule(
            new DefaultTaxRule(),
            new NoTaxRule(),
            new ImportedTaxRule()
        );

        return new Cart($taxRules);
    }

    public function testCaseOne()
    {
        $cart = $this->getCart();
        $cart->addProduct(new Product('book', 12.49, [SellableGoodInterface::CATEGORY_BOOK]), 2);
        $cart->addProduct(new Product('music CD', 14.99));
        $cart->addProduct(new Product('chocolate bar', 0.85, [SellableGoodInterface::CATEGORY_FOOD]));

        $this->assertEquals(42.32, $cart->getTotalCost());
        $this->assertEquals(1.50, $cart->getTax());
    }

    public function testCaseTwo()
    {
        $cart = $this->getCart();
        $cart->addProduct(new Product('imported box of chocolates', 10.00, [SellableGoodInterface::CATEGORY_IMPORTED, SellableGoodInterface::CATEGORY_FOOD]));
        $cart->addProduct(new Product('imported bottle of perfume', 47.50, [SellableGoodInterface::CATEGORY_IMPORTED]));

        $this->assertEquals(65.15, $cart->getTotalCost());
        $this->assertEquals(7.65, $cart->getTax());
    }

    public function testCaseThree()
    {
        $cart = $this->getCart();
        $cart->addProduct(new Product('imported bottle of perfume', 27.99, [SellableGoodInterface::CATEGORY_IMPORTED]));
        $cart->addProduct(new Product('bottle of perfume', 18.99));
        $cart->addProduct(new Product('packet of headache pills', 9.75, [SellableGoodInterface::CATEGORY_MEDICAL]));
        $cart->addProduct(new Product('box of imported chocolates', 11.25, [SellableGoodInterface::CATEGORY_IMPORTED, SellableGoodInterface::CATEGORY_FOOD]), 3);

        $this->assertEquals(98.38, $cart->getTotalCost());
        $this->assertEquals(7.90, $cart->getTax());
    }
}
