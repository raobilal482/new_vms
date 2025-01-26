<?php

namespace App\Filament\Resources\TimeTrackingResource\Pages;

use App\Filament\Resources\TimeTrackingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimeTracking extends EditRecord
{
    protected static string $resource = TimeTrackingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
