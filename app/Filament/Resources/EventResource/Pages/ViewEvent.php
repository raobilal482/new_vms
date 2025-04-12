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

Section::make('Event Organizer Information')
->schema([
    TextEntry::make('event_organizer.name')->label('Full Name'),
    TextEntry::make('event_organizer.email')->label('Email Address'),
    TextEntry::make('event_organizer.phone')->label('Phone Number'),
    TextEntry::make('event_organizer.availability')->label('Availability'),
    TextEntry::make('event_organizer.skills')->label('Skills'),
    TextEntry::make('event_organizer.preferred_roles')->label('Preferred Roles'),
    TextEntry::make('event_organizer.is_active')
        ->label('Status')
        ->formatStateUsing(fn($state) => $state ? 'Active' : 'Inactive')
        ->badge()
        ->color(fn ($state) => $state ? 'success' : 'danger'),
    TextEntry::make('event_organizer.languages')->label('Languages'),
])
->columns(2),

Section::make('Manager Information')
->schema([
    TextEntry::make('manager.name')->label('Full Name'),
    TextEntry::make('manager.email')->label('Email Address'),
    TextEntry::make('manager.phone')->label('Phone Number'),
    TextEntry::make('manager.availability')->label('Availability'),
    TextEntry::make('manager.skills')->label('Skills'),
    TextEntry::make('manager.preferred_roles')->label('Preferred Roles'),
    TextEntry::make('manager.is_active')
        ->label('Status')
        ->formatStateUsing(fn($state) => $state ? 'Active' : 'Inactive')
        ->badge()
        ->color(fn ($state) => $state ? 'success' : 'danger'),
    TextEntry::make('manager.languages')->label('Languages'),
])
->columns(2),

Section::make('Volunteer Information')
->schema([
    RepeatableEntry::make('volunteers')
        ->schema([
            TextEntry::make('name')->label('Full Name'),
            TextEntry::make('email')->label('Email Address'),
            TextEntry::make('phone')->label('Phone Number'),
            TextEntry::make('availability')->label('Availability'),
            TextEntry::make('skills')->label('Skills'),
            TextEntry::make('preferred_roles')->label('Preferred Roles'),
            TextEntry::make('is_active')
                ->label('Status')
                ->formatStateUsing(fn($state) => $state ? 'Active' : 'Inactive')
                ->badge()
                ->color(fn ($state) => $state ? 'success' : 'danger'),
            TextEntry::make('languages')->label('Languages'),
        ])
        ->columns(3)
])
->columns(1),

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
