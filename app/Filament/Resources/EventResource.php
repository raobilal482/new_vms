<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventResource extends Resource
{
    use Translatable;
    protected static ?string $model = Event::class;
    public static function getTranslatableLocales(): array
    {
        return ['en', 'lt'];
    }
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Events';
    protected static ?string $pluralLabel = 'Events';
    protected static ?string $slug = 'events';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Event Details')
                ->schema([
                    // First Column
                    TextInput::make('title')
                        ->label('Event Title')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('description')
                        ->label('Description')
                        ->maxLength(1000),
                    TextInput::make('location')
                        ->label('Location')
                        ->required()
                        ->maxLength(255),
                    DateTimePicker::make('start_time')
                        ->label('Start Time')
                        ->required(),
                    DateTimePicker::make('end_time')
                        ->label('End Time')
                        ->required(),
                    TextInput::make('max_volunteers')
                        ->label('Max Volunteers')
                        ->numeric()
                        ->required(),
                    Select::make('event_type')
                        ->label('Event Type')
                        ->options([
                            'General' => 'General',
                            'Fundraiser' => 'Fundraiser',
                            'Workshop' => 'Workshop',
                        ])
                        ->default('General')
                        ->required(),
                    Select::make('status')
                        ->label('Event Status')
                        ->options([
                            'Upcoming' => 'Upcoming',
                            'Completed' => 'Completed',
                            'Cancelled' => 'Cancelled',
                        ])
                        ->default('Upcoming')
                        ->required(),
                    // Second Column
                    Select::make('manager_id')
                        ->label('Manager')
                        ->relationship('manager', 'name')
                        ->nullable(),
                    Checkbox::make('is_virtual')
                        ->label('Is Virtual?'),
                    TextInput::make('platform_link')
                        ->label('Platform Link')
                        ->nullable()
                        ->url(),
                    Textarea::make('requirements')
                        ->label('Event Requirements')
                        ->nullable(),
                    TextInput::make('contact_email')
                        ->label('Contact Email')
                        ->nullable()
                        ->email(),
                    TextInput::make('contact_phone')
                        ->label('Contact Phone')
                        ->nullable(),
                    Textarea::make('tags')
                        ->label('Tags')
                        ->nullable(),
                    TextInput::make('duration')
                        ->label('Event Duration (in minutes)')
                        ->numeric()
                        ->nullable(),
                ])
                ->columns(2),
        ]);
    }


    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->label('Event Title')
                    ->wrap(),

                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->label('Description')
                    ->wrap(),

                Tables\Columns\TextColumn::make('location')
                    ->label('Location')
                    ->wrap(),

                Tables\Columns\TextColumn::make('start_time')
                    ->dateTime('M d, Y H:i')
                    ->label('Start Time'),

                Tables\Columns\TextColumn::make('end_time')
                    ->dateTime('M d, Y H:i')
                    ->label('End Time'),

                Tables\Columns\TextColumn::make('max_volunteers')
                    ->label('Max Volunteers'),

                Tables\Columns\TextColumn::make('event_type')
                    ->label('Event Type'),

                Tables\Columns\BooleanColumn::make('is_virtual')
                    ->label('Is Virtual'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status'),

                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration (min)'),

                Tables\Columns\TextColumn::make('tags')
                    ->label('Tags'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Upcoming' => 'Upcoming',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->label('Event Status'),
            ])
            ->actions([
                ActionGroup::make([

                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->label('Actions') // This is the label for the dropdown button
                ->icon('heroicon-o-chevron-down'),
            ])
            ->headerActions([
                Tables\Actions\LocaleSwitcher::make()
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'view' => Pages\ViewEvent::route('/{record}'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
