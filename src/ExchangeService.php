<?php

declare(strict_types=1);

namespace App;

/**
 * Currency exchange service.
 */
class ExchangeService
{
    private const SUPPORTED_CURRENCIES = ['BRL', 'USD', 'EUR'];
    private const CURRENCY_SYMBOLS = [
        'BRL' => 'R$',
        'USD' => '$',
        'EUR' => '€'
    ];

    /**
     * Convert value from one currency to another.
     */
    public function convert(string $amount, string $from, string $to, string $rate): array
    {
        $this->validateAmount($amount);
        $this->validateCurrency($from, 'source currency');
        $this->validateCurrency($to, 'target currency');
        $this->validateRate($rate);

        $amountFloat = (float) $amount;
        $rateFloat = (float) $rate;

        $convertedValue = $amountFloat * $rateFloat;

        return [
            'valorConvertido' => $convertedValue,
            'simboloMoeda' => self::CURRENCY_SYMBOLS[$to]
        ];
    }

    private function validateAmount(string $amount): void
    {
        if (!is_numeric($amount)) {
            throw new \InvalidArgumentException('Amount must be numeric');
        }

        $amountFloat = (float) $amount;
        if ($amountFloat <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero');
        }
    }

    private function validateCurrency(string $currency, string $context): void
    {
        if (!in_array($currency, self::SUPPORTED_CURRENCIES, true)) {
            throw new \InvalidArgumentException("{$context} not supported");
        }
    }

    private function validateRate(string $rate): void
    {
        if (!is_numeric($rate)) {
            throw new \InvalidArgumentException('Exchange rate must be numeric');
        }

        $rateFloat = (float) $rate;
        if ($rateFloat <= 0) {
            throw new \InvalidArgumentException('Exchange rate must be greater than zero');
        }
    }
} 