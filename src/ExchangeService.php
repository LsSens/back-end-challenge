<?php

declare(strict_types=1);

namespace App;

/**
 * Serviço para conversão de moedas.
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
     * Converte um valor de uma moeda para outra.
     */
    public function convert(string $amount, string $from, string $to, string $rate): array
    {
        $this->validateAmount($amount);
        $this->validateCurrency($from, 'moeda de origem');
        $this->validateCurrency($to, 'moeda de destino');
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
            throw new \InvalidArgumentException('Valor deve ser numérico');
        }

        $amountFloat = (float) $amount;
        if ($amountFloat <= 0) {
            throw new \InvalidArgumentException('Valor deve ser maior que zero');
        }
    }

    private function validateCurrency(string $currency, string $context): void
    {
        $currency = strtoupper($currency);
        if (!in_array($currency, self::SUPPORTED_CURRENCIES, true)) {
            throw new \InvalidArgumentException("Moeda {$context} não suportada");
        }
    }

    private function validateRate(string $rate): void
    {
        if (!is_numeric($rate)) {
            throw new \InvalidArgumentException('Taxa de câmbio deve ser numérica');
        }

        $rateFloat = (float) $rate;
        if ($rateFloat <= 0) {
            throw new \InvalidArgumentException('Taxa de câmbio deve ser maior que zero');
        }
    }
} 