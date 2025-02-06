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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationLabel = 'Tasks';
    protected static ?string $navigationIcon = 'heroicon-s-calendar';
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
                ->relationship('event', 'title')
                ->required(),

            // Multi-select for volunteers (many-to-many relation)
            Select::make('volunteers')
                ->label('Assign Volunteers')
                ->searchable()
                ->multiple()
                ->relationship('volunteers', 'name')
                ->required()
                ->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Display the task description
                TextColumn::make('description')
                    ->label('Task Description')
                    ->searchable()
                    ->sortable(),

                // Display the task status with a human-readable label
                TextColumn::make('status')
                    ->label('Task Status')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'assigned' => 'Assigned',
                        'in progress' => 'In Progress',
                        'completed' => 'Completed',
                    })
                    ->sortable()
                    ->badge()
                    ,

                // Display the related event's title
                TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable()
                    ->sortable(),

                // Display the names of assigned volunteers (many-to-many relationship)
                TextColumn::make('volunteers.name')
                    ->label('Assigned Volunteers')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(3) // Limit the number of volunteers displayed in the table
                    ->expandableLimitedList(), // Allow expanding the list if there are more volunteers
            ])
            ->filters([
                // Add filters here if needed
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
