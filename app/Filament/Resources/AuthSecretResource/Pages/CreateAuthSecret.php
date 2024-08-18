<?php

namespace App\Filament\Resources\AuthSecretResource\Pages;

use App\Filament\Resources\AuthSecretResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAuthSecret extends CreateRecord
{
    protected static string $resource = AuthSecretResource::class;
}
