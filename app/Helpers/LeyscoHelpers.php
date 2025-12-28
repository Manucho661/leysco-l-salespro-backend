<?php

namespace App\Helpers;

use Carbon\Carbon;

class LeyscoHelpers
{
    /**
     * Format a number into Kenyan currency
     *
     * @param float $amount
     * @return string
     */
    public static function formatCurrency(float $amount): string
    {
        return 'KES ' . number_format($amount, 2) . ' /=';
    }

    /**
     * Generate a unique order number
     * Format: ORD-YYYY-MM-XXX
     *
     * @return string
     */
    public static function generateOrderNumber(): string
    {
        $datePart = Carbon::now()->format('Y-m');
        $randomPart = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        return "ORD-{$datePart}-{$randomPart}";
    }

    /**
     * Calculate tax for a given amount and rate
     *
     * @param float $amount
     * @param float $rate
     * @return float
     */
    public static function calculateTax(float $amount, float $rate): float
    {
        return round(($amount * $rate) / 100, 2);
    }
}
