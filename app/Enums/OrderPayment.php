<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum OrderPayment: string implements HasLabel, HasColor, HasIcon
{
    case Paid = 'Paid';
    case Unpaid = 'Unpaid';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Paid => 'Paid',
            self::Unpaid => 'Unpaid',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Paid => 'success',
            self::Unpaid => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Paid => 'heroicon-m-currency-dollar',
            self::Unpaid => 'heroicon-m-currency-yen'
        };
    }
}
