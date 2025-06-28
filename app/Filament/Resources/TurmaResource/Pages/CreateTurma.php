<?php

namespace App\Filament\Resources\TurmaResource\Pages;

use App\Filament\Resources\TurmaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTurma extends CreateRecord
{
    protected static string $resource = TurmaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}