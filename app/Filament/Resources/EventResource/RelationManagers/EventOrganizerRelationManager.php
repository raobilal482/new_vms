<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventOrganizerRelationManager extends RelationManager
{
    protected static string $relationship = 'event_organizer';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Full Name'),
                TextInput::make('email')->label('Email Address'),
                TextInput::make('phone')->label('Phone Number'),
                TextInput::make('availability')->label('Availability'),
                TextInput::make('skills')->label('Skills'),
                TextInput::make('preferred_roles')->label('Preferred Roles'),
                TextInput::make('is_active')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state ? 'Active' : 'Inactive')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger'),
                TextInput::make('languages')->label('Languages'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
