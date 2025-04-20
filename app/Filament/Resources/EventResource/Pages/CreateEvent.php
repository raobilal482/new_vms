<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Actions\Action as ActionsAction;
use Filament\Actions\Modal\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Broadcasting\Broadcasters\NullBroadcaster;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['created_by'] = auth()->id();

    return $data;
}


protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}

}
