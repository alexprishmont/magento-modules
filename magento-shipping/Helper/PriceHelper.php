<?php
declare(strict_types=1);

namespace Alexpr\SimpleShipping\Helper;

use Magento\Directory\Model\CurrencyFactory;

class PriceHelper
{
    private $currencyFactory;

    public function __construct(CurrencyFactory $currencyFactory)
    {
        $this->currencyFactory = $currencyFactory->create();
    }

    public function convertPrice(
        float $amount,
        string $fromCurrency,
        string $toCurrency,
        string $roundRule = null
    ): float {
        $rateToBase = $this->currencyFactory
            ->load($fromCurrency)
            ->getAnyRate($toCurrency);

        $converted = $amount * $rateToBase;

        if ($roundRule) {
            return $this->round($converted, $roundRule);
        }
        return $converted;
    }

    private function round(float $amount, string $roundRule): float
    {
        return ($roundRule === 'up') ? ceil($amount) : floor($amount);
    }
}