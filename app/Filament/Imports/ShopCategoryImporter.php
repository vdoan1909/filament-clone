<?php

namespace App\Filament\Imports;

use App\Models\Shop\ShopCategory;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Log;

class ShopCategoryImporter extends Importer
{
    protected static ?string $model = ShopCategory::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('description'),
            ImportColumn::make('seo_title')
                ->label('SEO title')
                ->rules(['max:60']),
            ImportColumn::make('seo_description')
                ->label('SEO description')
                ->rules(['max:160']),
            ImportColumn::make('is_active')
                ->label('Active Status')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
        ];
    }

    public function resolveRecord(): ?ShopCategory
    {
        return ShopCategory::firstOrNew([
            'name' => $this->data['name'],
        ]);

        // return new ShopCategory();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your category import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
