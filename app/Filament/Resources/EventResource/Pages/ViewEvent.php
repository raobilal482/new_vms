<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewEvent extends ViewRecord
{
    protected static string $resource = EventResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make("")
                    ->schema([
                        Section::make('Event Details')
                            ->schema([
                                TextEntry::make('title')
                                    ->label('Event Title'),

                                TextEntry::make('description')
                                    ->label('Description'),

                                TextEntry::make('location')
                                    ->label('Location'),

                                TextEntry::make('start_time')
                                    ->label('Start Time')
                                    ->dateTime(),

                                TextEntry::make('end_time')
                                    ->label('End Time')
                                    ->dateTime(),

                                TextEntry::make('duration')
                                    ->label('Duration (Minutes)'),

                                TextEntry::make('status')
                                    ->label('Status')
                                    ->colors([
                                        'primary' => 'Upcoming',
                                        'success' => 'Completed',
                                        'danger' => 'Cancelled',
                                    ])
                                    ->badge(),

                                TextEntry::make('event_type')
                                    ->label('Event Type'),

                            ])->columns(2),

                        Section::make("Other Information")
                            ->schema([

                                TextEntry::make('max_volunteers')
                                    ->label('Max Volunteers'),

                                TextEntry::make('contact_email')
                                    ->label('Contact Email'),

                                TextEntry::make('contact_phone')
                                    ->label('Contact Phone'),

                                TextEntry::make('requirements')
                                    ->label('Requirements'),

                                TextEntry::make('tags')
                                    ->label('Tags'),


                                TextEntry::make('is_virtual')
                                    ->label('Is Virtual?'),

                                TextEntry::make('platform_link')
                                    ->label('Platform Link'),

                                // ListEntry::make('manager.name')
                                //     ->label('Manager Name'),
                            ])->columns(2)

                    ])->columns(2)
            ]);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
