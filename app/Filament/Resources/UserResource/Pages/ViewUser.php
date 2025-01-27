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
                TextEntry::make('date_of_birth')->label('Date of Birth')->date(),
                TextEntry::make('address')->label('Address'),
                TextEntry::make('availability')->label('Availability'),
                TextEntry::make('skills')->label('Skills'),
                TextEntry::make('preferred_roles')->label('Preferred Roles'),
                TextEntry::make('is_active')
                ->label('Active')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive')
                    ->badge(),
                    TextEntry::make('languages')->label('Languages'),
                    TextEntry::make('emergency_contact_name')->label('Emergency Contact Name'),
                TextEntry::make('emergency_contact_phone')->label('Emergency Contact Phone'),
                TextEntry::make('motivation')->label('Motivation'),
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
