<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Infolist;

class ViewTask extends ViewRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    // Corrected: Make this method non-static
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Task Details')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Task Description'),

                        TextEntry::make('status')
                            ->label('Task Status')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'assigned' => 'Assigned',
                                'in progress' => 'In Progress',
                                'completed' => 'Completed',
                            })
                            ->badge(),

                        TextEntry::make('event.title')
                            ->label('Event')
                            ->url(fn ($record) => route('filament.admin.resources.events.view', $record->event->id))
                            ->color('primary'),

                        TextEntry::make('volunteers.name')
                            ->label('Assigned Volunteers')
                            ->listWithLineBreaks()
                            ->bulleted(),
                    ])->columns(2),
            ]);
    }
}
