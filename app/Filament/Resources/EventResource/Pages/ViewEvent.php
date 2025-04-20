<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Enums\UserTypeEnum;
use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewEvent extends ViewRecord
{
    protected static string $resource = EventResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
    return $infolist->schema([
        Section::make('Event Information')
            ->schema([
                TextEntry::make('title')
                    ->label('Event Title'),
                TextEntry::make('description')
                    ->label('Description')
                    ->markdown()
                    ->hidden(fn ($record) => empty($record->description)),
                TextEntry::make('location')
                    ->label('Location'),
                    TextEntry::make('start_time')
                    ->label('Start Time')
                    ->formatStateUsing(fn ($state) => date('g:i A', strtotime($state))),
                TextEntry::make('end_time')
                    ->label('End Time')
                    ->date(),
                TextEntry::make('max_volunteers')
                    ->label('Max Volunteers'),
                TextEntry::make('type')
                    ->label('Event Type'),
                TextEntry::make('status')
                    ->label('Event Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Upcoming' => 'success',
                        'Completed' => 'gray',
                        'Cancelled' => 'danger',
                    }),
                TextEntry::make('duration')
                    ->label('Event Duration')
                    ->suffix('minutes')
                    ->hidden(fn ($record) => empty($record->duration)),
            ])
            ->columns(2),

        Section::make('Other Information')
            ->schema([
                TextEntry::make('contact_email')
                    ->label('Contact Email')
                    ->hidden(fn ($record) => empty($record->contact_email)),
                TextEntry::make('contact_phone')
                    ->label('Contact Phone')
                    ->hidden(fn ($record) => empty($record->contact_phone)),
                TextEntry::make('platform_link')
                    ->label('Platform Link')
                    ->hidden(fn ($record) => empty($record->platform_link)),
                TextEntry::make('requirements')
                    ->label('Event Requirements')
                    ->markdown()
                    ->hidden(fn ($record) => empty($record->requirements)),
                TextEntry::make('tags')
                    ->label('Tags')
                    ->hidden(fn ($record) => empty($record->tags)),
                TextEntry::make('is_virtual')
                    ->label('Is Virtual?'),
            ])
            ->columns(2),
    ]);
}

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
            ->visible(function (){
                if(auth()->user()->type == UserTypeEnum::MANAGER->value){
                    return false;
                }
                return true;
            }),
        ];
    }
}
