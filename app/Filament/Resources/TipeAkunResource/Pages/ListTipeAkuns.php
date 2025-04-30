<?php

namespace App\Filament\Resources\TipeAkunResource\Pages;

use App\Filament\Resources\TipeAkunResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipeAkuns extends ListRecords
{
    protected static string $resource = TipeAkunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->hidden(fn () => !auth()->user()->hasRole('admin')),
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->color('primary'),
        ];
    }
}