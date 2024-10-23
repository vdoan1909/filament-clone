<?php

namespace App\Filament\Imports;

use App\Models\Shop\ShopCategory;
use Exception;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Log;

class ShopCategoryImporter extends Importer
{
    protected static ?string $model = ShopCategory::class;

    // những cột lấy để nhập dữ liệu
    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Name')
                ->requiredMapping()
                ->rules([
                    'required',
                    'unique:shop_categories,name',
                    'max:100'
                ]),
            ImportColumn::make('description')
                ->label('Description')
                ->requiredMapping()
                ->rules([
                    'nullable'
                ]),
            ImportColumn::make('seo_title')
                ->label('SEO Title')
                ->requiredMapping()
                ->rules([
                    'nullable',
                    'max:100'
                ]),
            ImportColumn::make('seo_description')
                ->label('SEO Description')
                ->requiredMapping()
                ->rules([
                    'nullable'
                ]),

            ImportColumn::make('is_active')
                ->label('Active Status')
                ->requiredMapping()
                ->rules([
                    'nullable',
                    'boolean'
                ])
        ];
    }

    // tạo mới dữ liệu sau khi lấy
    public function resolveRecord(): ?ShopCategory
    {
        try {
            dd($this->data);

            $category = ShopCategory::firstOrNew([
                'name' => $this->data['name'],
            ]);

            $category->fill($this->data);
            $category->save();

            return $category;
        } catch (Exception $e) {
            Log::error('Import Error: ' . $e->getMessage());
            throw $e;
        }
    }


    // in ra thông báo
    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your shop category import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
