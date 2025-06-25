<?php

namespace App\Filament\Resources\AtestadoResource\Pages;

use App\Exceptions\SubstitutoIgualServidorException;
use App\Filament\Resources\AtestadoResource;
use App\Services\AtestadoService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAtestado extends EditRecord
{
    protected static string $resource = AtestadoResource::class;

    public function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['servidor_id'] === $data['substituto_id']) {
            Notification::make()
                ->title('O servidor não pode substituir a si mesmo.')
                ->danger()
                ->persistent()
                ->send();

            $this->halt();
        }


        $data['quantidade_dias'] = AtestadoService::calcularQuantidadeDias(
            $data['data_inicio'] ?? null,
            $data['data_fim'] ?? null,
            $data['prazo_indeterminado'] ?? false,
        );

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
