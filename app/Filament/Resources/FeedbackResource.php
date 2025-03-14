<?php

namespace App\Filament\Resources;

use App\Enums\UserTypeEnum;
use App\Filament\Resources\FeedbackResource\Pages;
use App\Models\Feedback;
use Filament\Actions\ViewAction;
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
                // First dropdown: Select the feedback type
                Select::make('feedback_type')
                    ->label('Give Feedback On')
                    ->options([
                        'event' => 'Event',
                        'task' => 'Task',
                        UserTypeEnum::VOLUNTEER->value => UserTypeEnum::VOLUNTEER->value,
                        UserTypeEnum::EVENT_ORGANIZER->value => UserTypeEnum::EVENT_ORGANIZER->value,
                        UserTypeEnum::MANAGER->value => UserTypeEnum::MANAGER->value,
                    ])
                    ->reactive() // Makes it reactive to trigger updates
                    ->required()
                    ->default('event'), // Optional: set a default

                // Dynamic dropdowns based on feedback_type
                Select::make('event_id')
                    ->label('Event')
                    ->options(\App\Models\Event::pluck('title', 'id'))
                    ->searchable()
                    ->nullable()
                    ->visible(fn ($get) => $get('feedback_type') === 'event'), // Only show if feedback_type is 'event'

                Select::make('task_id')
                    ->label('Task')
                    ->options(\App\Models\Task::pluck('title', 'id'))
                    ->searchable()
                    ->nullable()
                    ->visible(fn ($get) => $get('feedback_type') === 'task'), // Only show if feedback_type is 'task'

                Select::make('volunteer_id')
                    ->label('Volunteer')
                    ->options(\App\Models\User::where('type',UserTypeEnum::VOLUNTEER->value)->pluck('name', 'id')) // Adjust based on your User model logic
                    ->searchable()
                    ->nullable()
                    ->visible(fn ($get) => $get('feedback_type') === UserTypeEnum::VOLUNTEER->value), // Only show if feedback_type is 'volunteer'

                Select::make('organizer_id')
                    ->label('Event Organizer')
                    ->options(\App\Models\User::where('type', UserTypeEnum::EVENT_ORGANIZER->value)->pluck('name', 'id')) // Adjust based on your User model logic
                    ->searchable()
                    ->nullable()
                    ->visible(fn ($get) => $get('feedback_type') === UserTypeEnum::EVENT_ORGANIZER->value), // Only show if feedback_type is 'organizer'

                Select::make('manager_id')
                    ->label('Manager')
                    ->options(\App\Models\User::where('type', UserTypeEnum::MANAGER->value)->pluck('name', 'id')) // Adjust based on your User model logic
                    ->searchable()
                    ->nullable()
                    ->visible(fn ($get) => $get('feedback_type') === UserTypeEnum::MANAGER->value), // Only show if feedback_type is 'manager'

                // Auto-set giver_id to the logged-in user
                Select::make('giver_id')
                    ->label('Feedback Provider')
                    ->options(\App\Models\User::pluck('name', 'id'))
                    ->default(fn () => auth()->id())
                    ->disabled()
                    ->required(),

                // Comment and Rating fields
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
        ->recordUrl(Null)
            ->columns([
                TextColumn::make('giver.name')->label('From'),
                // Dynamically display the receiver based on feedback_type
                TextColumn::make('feedback_type')
                    ->label('Feedback On')
                    ->formatStateUsing(function ($record) {
                        return match ($record->feedback_type) {
                            // dump($record->feedback_type),
                            'event' => 'Event: ' . ($record->event->title ?? 'N/A'),
                            'task' => 'Task: ' . ($record->task->title ?? 'N/A'),
                            UserTypeEnum::VOLUNTEER->value => 'Volunteer: ' . ($record->volunteer->name ?? 'N/A'),
                            UserTypeEnum::EVENT_ORGANIZER->value => 'Event Organizer: ' . ($record->organizer->name ?? 'N/A'),
                            UserTypeEnum::MANAGER->value => 'Manager: ' . ($record->manager->name ?? 'N/A'),
                            default => 'N/A',
                        };
                    }),
                TextColumn::make('comment')->limit(50),
                TextColumn::make('rating')
                ->default('N/A')
                ->formatStateUsing(function ($state) {
                    if ($state === 'N/A' || !$state) {
                        return 'N/A';
                    }
                    $rating = (int) $state;
                    $maxStars = 5;
                    $filledStars = str_repeat('<span class="text-yellow-500">★</span>', min($rating, $maxStars));
                    $emptyStars = str_repeat('<span class="text-gray-300">☆</span>', $maxStars - min($rating, $maxStars));
                    return $filledStars . $emptyStars;
                })
                ->html(),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
             ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->icon('heroicon-o-chevron-down'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]); // Makes the row clickable
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeedback::route('/'),
            'create' => Pages\CreateFeedback::route('/create'),
            'view' => Pages\ViewFeedback::route('/{record}'),
            'edit' => Pages\EditFeedback::route('/{record}/edit'),
        ];
    }
}
