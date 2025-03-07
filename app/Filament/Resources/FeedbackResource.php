<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedbackResource\Pages;
use App\Models\Feedback;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('giver_id')
                    ->label('Feedback Provider')
                    ->options(\App\Models\User::pluck('name', 'id'))
                    ->default(auth()->id())
                    ->disabled() // Auto-set to current user
                    ->required(),
                Select::make('receiver_id')
                    ->label('Feedback Receiver')
                    ->options(\App\Models\User::pluck('name', 'id'))
                    ->nullable()
                    ->searchable(),
                Select::make('event_id')
                    ->label('Event')
                    ->options(\App\Models\Event::pluck('title', 'id'))
                    ->nullable()
                    ->searchable(),
                Select::make('task_id')
                    ->label('Task')
                    ->options(\App\Models\Task::pluck('title', 'id'))
                    ->nullable()
                    ->searchable(),
                Textarea::make('comment')
                    ->label('Feedback')
                    ->required()
                    ->columnSpanFull(),
                Select::make('rating')
                    ->label('Rating')
                    ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'])
                    ->nullable(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('giver.name')->label('From'),
                TextColumn::make('receiver.name')->label('To')->default('N/A'),
                TextColumn::make('event.title')->label('Event')->default('N/A'),
                TextColumn::make('task.title')->label('Task')->default('N/A'),
                TextColumn::make('comment')->limit(50),
                TextColumn::make('rating')->default('N/A'),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeedback::route('/'),
            'create' => Pages\CreateFeedback::route('/create'),
            'edit' => Pages\EditFeedback::route('/{record}/edit'),
        ];
    }
}