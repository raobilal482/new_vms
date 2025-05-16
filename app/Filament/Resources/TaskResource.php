<?php

namespace App\Filament\Resources;

use App\Enums\UserTypeEnum;
use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use App\Models\TimeTracking;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationLabel = 'Tasks';
    protected static ?string $navigationIcon = 'heroicon-s-calendar';

    public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()
            ->schema([


                TextInput::make('title')
                ->label('Task Title')
                ->required()
                ->maxLength(255),

                TextInput::make('description')
                ->label('Task Description')
                ->required()
                ->maxLength(255),

                Select::make('event_id')
                    ->label('Event')
                    ->relationship(
                        name: 'event',
                        titleAttribute: 'title',
                        modifyQueryUsing: function ($query) {
                            $user = auth()->user();
                            if ($user->type === UserTypeEnum::EVENT_ORGANIZER->value) {

                                return $query->where('created_by', $user->id)
                                ->where('is_approved', 'Approved');
                            }
                            return $query->where('is_approved', 'Approved');
                        }
                    )
                    ->required(),
                // Multi-select for volunteers (many-to-many relation)
                Select::make('volunteers')
                ->label('Assign Volunteers')
                ->searchable()
                ->multiple()
                ->relationship('volunteers', 'name')
                ->required()
                ->preload(),
        ])->columns(2),
    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(function (Builder $query) {
            $query->whereHas('event', function (Builder $eventQuery) {
                $eventQuery->where('is_approved', 'Approved');
            });
            $query->where('status', '!=', 'Not Picked');
        })
        ->columns([

                TextColumn::make('title')
                ->label('Task Title')
                ->searchable()
                ->sortable(),

                TextColumn::make('description')
                ->label('Task Description')
                ->searchable()
                ->sortable(),


                // Display the task status with a human-readable label
                TextColumn::make('status')
                    ->label('Task Status')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'Accepted' => 'Accepted',
                        'waiting_for_approval' => 'Waiting for Approval',
                        'Not Picked' => 'Not Picked',
                        'assigned' => 'Assigned',
                        'in progress' => 'In Progress',
                        'completed' => 'Completed',
                        'task picked' => 'Task Picked',
                        'reject' => 'Rejected',
                        default => 'Unknown',
                    })
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Accepted' => 'success',
                        'waiting_for_approval' => 'info',
                        'Not Picked' => 'danger',
                        'assigned' => 'gray',
                        'pending' => 'gray',
                        'in progress' => 'warning',
                        'completed' => 'success',
                        'task picked' => 'info',
                        'rejected' => 'danger'
                    }),

                // Display the related event's title
                TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('give_feedback')
                        ->label('Give Feedback')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->form([
                            Textarea::make('comment')
                                ->label('Feedback')
                                ->required()
                                ->maxLength(500)
                                ->columnSpanFull(),
                            Select::make('rating')
                                ->label('Rating')
                                ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'])
                                ->nullable(),
                        ])
                        ->action(function (Task $record, array $data) {
                            \App\Models\Feedback::create([
                                'giver_id' => auth()->id(),
                                'task_id' => $record->id,
                                'comment' => $data['comment'],
                                'rating' => $data['rating'],
                                'feedback_type' => 'task',
                            ]);
                            \Filament\Notifications\Notification::make()
                                ->title('Feedback Submitted')
                                ->success()
                                ->send();
                        })
                        ->modalHeading('Provide Feedback on Event')
                        ->modalSubmitActionLabel('Submit Feedback')
                        ->modalWidth('lg'),

                    Tables\Actions\Action::make('pick_task')
                    ->label('Pick Task')
                    ->visible(fn () => Auth::user()->type === UserTypeEnum::VOLUNTEER->value)
                    // ->visible(fn (Task $record) => $record->status === 'Accecpted' || $record->status == 'Not Picked') // Only visible to volunteers
                    ->hidden(condition: fn (Task $record) => $record->status == 'completed' || $record->status == 'task picked' || $record->status == 'in progress' || $record->status =='rejected') // Only show if the task is assigned
                    ->icon('heroicon-o-check')
                    ->requiresConfirmation() // Shows a confirmation modal
                    ->modalHeading('Pick This Task')
                    ->modalDescription('Do you want to pick this task? This will start time tracking.')
                    ->modalSubmitActionLabel('Yes, Pick Task')
                    ->modalCancelActionLabel('No, Cancel')
                    ->action(function (Task $record) {
                        $volunteer = Auth::user();

                        // Create TimeTracking record
                        $timetracking = TimeTracking::create([
                            'volunteer_id' => $volunteer->id,
                            'task_id' => $record->id,
                            'checkin_time' => null,
                            'checkout_time' => null,
                            'break_included' => false,
                            'break_duration_minutes' => 0,
                            'hours_logged' => 0,
                        ]);

                        $timetracking->task->update([
                            'status' => 'task picked',
                        ]);

                        \Filament\Notifications\Notification::make()
                        ->title('Task Picked')
                        ->body('Time tracking created. Please check in before doing this task.')
                        ->success()
                        ->send();
                    })
                    ->color('success'), // Green button

                    Tables\Actions\Action::make('manage_task_approval')
                    ->label('Manage Task Approval')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (Task $record) => Auth::user()->type !== UserTypeEnum::VOLUNTEER->value && $record->status === 'waiting_for_approval') // Only show if the task is waiting for approval
                    ->form([
                        Select::make('action')
                            ->label('Action')
                            ->options([
                                'accept' => 'Accept',
                                'reject' => 'Reject',
                            ])
                            ->required()
                            ->live(),
                        Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->required(fn ($get) => $get('action') === 'reject')
                            ->hidden(fn ($get) => $get('action') === 'accept' || !$get('action'))
                            ->maxLength(500),
                    ])
                    ->action(function (Task $record, array $data) {
                        if ($data['action'] === 'accept') {
                            $record->update([
                                'status' => 'Accepted',
                            ]);
                            \Filament\Notifications\Notification::make()
                                ->title('Task Accepted')
                                ->body('The task has been approved and is now in progress.')
                                ->success()
                                ->send();
                        } elseif ($data['action'] === 'reject') {
                            $record->update([
                                'status' => 'rejected',
                                'rejection_reason' => $data['rejection_reason'],
                            ]);
                            // Optionally, notify the volunteer who picked the task
                            \Filament\Notifications\Notification::make()
                                ->title('Task Rejected')
                                ->body('Reason: ' . $data['rejection_reason'])
                                ->warning()
                                ->send();
                        }
                    })
                    ->modalHeading('Manage Task Approval')
                    ->modalSubmitActionLabel('Confirm')
                    ->modalCancelActionLabel('Cancel')
                    ->modalWidth('lg')
                    ->color('primary'),

                ]),
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
