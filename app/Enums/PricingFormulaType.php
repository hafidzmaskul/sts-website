<?php

namespace App\Enums;

enum PricingFormulaType: string
{
    case MarginPercent = 'Margin in percent';
    case MarkupPercent = 'Markup in percent';
    case DiscountPercent = 'Discount in percent';

    public function label(): string
    {
        return match ($this) {
            self::MarginPercent => 'Margin (%)',
            self::MarkupPercent => 'Markup (%)',
            self::DiscountPercent => 'Discount (%)',
        };
    }
}
