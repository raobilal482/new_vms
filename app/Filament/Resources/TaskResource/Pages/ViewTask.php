<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Enums\UserTypeEnum;
use App\Filament\Resources\TaskResource;
use App\Models\Task;
use App\Models\TimeTracking;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;

class ViewTask extends ViewRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('pick_task')
                    ->label('Pick Task')
                    ->visible(fn () => Auth::user()->type === UserTypeEnum::VOLUNTEER->value) // Only visible to volunteers
                    ->hidden(condition: fn (Task $record) => $record->status == 'completed' || $record->status == 'task picked' || $record->status == 'in progress') // Only show if the task is assigned
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
                    ->color('success'),
        ];
    }

    // Corrected: Make this method non-static
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Task Details')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Task Description'),

                        TextEntry::make('status')
                            ->label('Task Status')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'assigned' => 'Assigned',
                                'in progress' => 'In Progress',
                                'completed' => 'Completed',
                                'task picked' => 'Task Picked',
                                default => 'Unknown',
                            })
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'assigned' => 'gray',
                                'pending' => 'gray',
                                'in progress' => 'warning',
                                'completed' => 'success',
                                'task picked' => 'info',
                            }),
                        TextEntry::make('event.title')
                            ->label('Event')
                            ->url(fn ($record) => route('filament.admin.resources.events.view', $record->event->id))
                            ->color('primary'),

                        TextEntry::make('volunteers.name')
                            ->label('Assigned Volunteers')
                            ->listWithLineBreaks()
                            ->bulleted(),
                    ])->columns(2),
            ]);
    }
}
