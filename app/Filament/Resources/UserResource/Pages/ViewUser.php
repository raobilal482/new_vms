<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        TextEntry::make('name')->label('Full Name'),
                        TextEntry::make('email')->label('Email Address'),
                        TextEntry::make('phone')->label('Phone Number'),
                        TextEntry::make('date_of_birth')
                            ->label('Date of Birth')
                            ->date()
                            ->visible(fn ($record) => $record->type === 'Volunteer'), // Show only for Volunteer
                        TextEntry::make('address')
                            ->label('Address')
                            ->visible(fn ($record) => $record->type === 'Volunteer'), // Show only for Volunteer
                        TextEntry::make('availability')->label('Availability'),
                        TextEntry::make('skills')
                            ->label('Skills')
                            ->visible(fn ($record) => $record->type === 'Volunteer'), // Show only for Volunteer
                        TextEntry::make('preferred_roles')
                            ->label('Preferred Roles')
                            ->visible(fn ($record) => $record->type === 'Volunteer'), // Show only for Volunteer
                        TextEntry::make('is_active')
                            ->label('Status')
                            ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive')
                            ->badge()
                            ->color(fn ($state) => $state ? 'success' : 'danger'),
                        TextEntry::make('languages')->label('Languages'),
                        TextEntry::make('emergency_contact_name')
                            ->label('Emergency Contact Name')
                            ->visible(fn ($record) => $record->type === 'Volunteer'), // Show only for Volunteer
                        TextEntry::make('emergency_contact_phone')
                            ->label('Emergency Contact Phone')
                            ->visible(fn ($record) => $record->type === 'Volunteer'), // Show only for Volunteer
                        TextEntry::make('motivation')
                            ->label('Motivation')
                            ->visible(fn ($record) => $record->type === 'Volunteer'), // Show only for Volunteer
                    ])->columns(3)
            ]);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
