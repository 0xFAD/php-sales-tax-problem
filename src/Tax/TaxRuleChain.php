<?php

namespace SalesTax\Tax;

use SalesTax\Cart\CartItem;
use SalesTax\Tax\Rule\DefaultTaxRule;
use SalesTax\Tax\Rule\ImportedTaxRule;
use SalesTax\Tax\Rule\NoTaxRule;
use SalesTax\Tax\Rule\TaxRuleInterface;

/**
 * Class TaxRuleChain
 * @package SalesTax\Tax
 */
class TaxRuleChain implements TaxRuleInterface, \IteratorAggregate
{
    /**
     * @var TaxRuleInterface[]
     */
    protected array $rules = [];

    /**
     * TaxRuleChain constructor.
     * @param array $rules
     */
    public function __construct(array $rules = [])
    {
        $this->rules = $rules;
    }

    /**
     * @param TaxRuleInterface ...$rules
     * @return $this
     */
    public function addRule(TaxRuleInterface ...$rules): self
    {
        $this->rules = $rules;
        return $this;
    }

    public function apply(CartItem $item)
    {
        foreach ($this->rules as $rule) {
            $rule->apply($item);
        }
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->rules);
    }

    /**
     * Build default tax rule chain
     *
     * @return static
     */
    public static function buildDefault(): self
    {
        return new self([
           new DefaultTaxRule(),
           new NoTaxRule(),
           new ImportedTaxRule()
        ]);
    }
}
