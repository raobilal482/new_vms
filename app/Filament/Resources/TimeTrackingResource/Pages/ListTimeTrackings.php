<?php

namespace App\Filament\Resources\TimeTrackingResource\Pages;

use App\Filament\Resources\TimeTrackingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTimeTrackings extends ListRecords
{
    protected static string $resource = TimeTrackingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
