<?php

namespace App\Filament\Resources\TipeAkunResource\Pages;

use App\Filament\Resources\TipeAkunResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipeAkun extends EditRecord
{
    protected static string $resource = TipeAkunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
