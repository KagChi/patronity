<?php

namespace App\Filament\Resources\AuthSecretResource\Pages;

use App\Filament\Resources\AuthSecretResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuthSecrets extends ListRecords
{
    protected static string $resource = AuthSecretResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
