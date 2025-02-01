<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationLabel = 'Tasks';
    // protected static ?string $navigationIcon = 'heroicon-s-clipboard-list';
    protected static ?string $navigationGroup = 'Event Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('description')
                ->label('Task Description')
                ->required()
                ->maxLength(255),

            Select::make('status')
                ->label('Task Status')
                ->options([
                    'assigned' => 'Assigned',
                    'in progress' => 'In Progress',
                    'completed' => 'Completed',
                ])
                ->default('assigned')
                ->required(),

            Select::make('event_id')
                ->label('Event')
                ->relationship('events', 'title')
                ->required(),

            // Multi-select for volunteers (many-to-many relation)
            Select::make('volunteers')
                ->label('Assigned Volunteers')
                ->multiple()
                ->relationship('volunteers', 'name')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'view' => Pages\ViewTask::route('/{record}'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
