<?php

namespace SalesTax\Command;

use SalesTax\Cart\Cart;
use SalesTax\Catalog\Product;
use SalesTax\Catalog\SellableGoodInterface;
use SalesTax\Tax\Rule\TaxRuleInterface;
use SalesTax\Tax\TaxRuleChain;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class PrintShoppingBasketCommand
 * @package SalesTax\Command
 */
final class PrintShoppingBasketCommand extends Command
{
    protected static $defaultName = 'basket';

    protected TaxRuleInterface $taxRuleChain;

    /**
     * PrintShoppingBasketCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->taxRuleChain = TaxRuleChain::buildDefault();
    }

    public function getDescription(): string
    {
        return 'A simple utility to print sales tax problem results';
    }

    /**
     * Build basket case one
     *
     * @return Cart
     */
    protected function createBasketOne(): Cart
    {
        $cart = new Cart($this->taxRuleChain);
        $cart->addProduct(new Product('book', 12.49, [SellableGoodInterface::CATEGORY_BOOK]), 2);
        $cart->addProduct(new Product('music CD', 14.99));
        $cart->addProduct(new Product('chocolate bar', 0.85, [SellableGoodInterface::CATEGORY_FOOD]));

        return $cart;
    }

    /**
     * Build basket case two
     *
     * @return Cart
     */
    protected function createBasketTwo(): Cart
    {
        $cart = new Cart($this->taxRuleChain);
        $cart->addProduct(new Product('imported box of chocolates', 10.00, [SellableGoodInterface::CATEGORY_IMPORTED, SellableGoodInterface::CATEGORY_FOOD]));
        $cart->addProduct(new Product('imported bottle of perfume', 47.50, [SellableGoodInterface::CATEGORY_IMPORTED]));

        return $cart;
    }

    /**
     * Build basket case three
     *
     * @return Cart
     */
    protected function createBasketThree(): Cart
    {
        $cart = new Cart($this->taxRuleChain);
        $cart->addProduct(new Product('imported bottle of perfume', 27.99, [SellableGoodInterface::CATEGORY_IMPORTED]));
        $cart->addProduct(new Product('bottle of perfume', 18.99));
        $cart->addProduct(new Product('packet of headache pills', 9.75, [SellableGoodInterface::CATEGORY_MEDICAL]));
        $cart->addProduct(new Product('box of imported chocolates', 11.25, [SellableGoodInterface::CATEGORY_IMPORTED, SellableGoodInterface::CATEGORY_FOOD]), 3);

        return $cart;
    }

    /**
     * Retrieve an array formatted for SymfonyStyle::table data
     *
     * @param Cart $cart
     * @return array
     * @throws \Exception
     */
    protected function getProductsLinesForTable(Cart $cart): array
    {
        return array_map(function ($item) {
            return [$item->getQuantity(), $item->getProduct()->getName(), $item->getTotalCost()];
        }, $cart->getIterator()->getArrayCopy());
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $io->title('OUTPUT 1:');
            $basketOne = $this->createBasketOne();
            $io->table(['Qty', 'Name', 'Price'], $this->getProductsLinesForTable($basketOne));
            $io->text(sprintf('Sales Taxes: %.2f', $basketOne->getTax()));
            $io->text(sprintf('Total: %.2f', $basketOne->getTotalCost()));

            $io->title('OUTPUT 2:');
            $basketTwo = $this->createBasketTwo();
            $io->table(['Qty', 'Name', 'Price'], $this->getProductsLinesForTable($basketTwo));
            $io->text(sprintf('Sales Taxes: %.2f', $basketTwo->getTax()));
            $io->text(sprintf('Total: %.2f', $basketTwo->getTotalCost()));

            $io->title('OUTPUT 3:');
            $basketThree = $this->createBasketThree();
            $io->table(['Qty', 'Name', 'Price'], $this->getProductsLinesForTable($basketThree));
            $io->text(sprintf('Sales Taxes: %.2f', $basketThree->getTax()));
            $io->text(sprintf('Total: %.2f', $basketThree->getTotalCost()));

            $io->newLine();

        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
