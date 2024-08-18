<?php

namespace App\Filament\Resources\AuthSecretResource\Pages;

use App\Filament\Resources\AuthSecretResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuthSecret extends EditRecord
{
    protected static string $resource = AuthSecretResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
