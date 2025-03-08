<?php

namespace App\Filament\Resources\FeedbackResource\Pages;

use App\Enums\UserTypeEnum;
use App\Filament\Resources\FeedbackResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;

class ViewFeedback extends ViewRecord
{
    protected static string $resource = FeedbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('giver.name')->label('Feedback Provider'),
                TextEntry::make('feedback_type')
                    ->label('Feedback Type')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'event' => 'Event',
                        'task' => 'Task',
                        UserTypeEnum::VOLUNTEER->value => UserTypeEnum::VOLUNTEER->value,
                        UserTypeEnum::EVENT_ORGANIZER->value => UserTypeEnum::EVENT_ORGANIZER->value,
                        UserTypeEnum::MANAGER->value => UserTypeEnum::MANAGER->value,
                        default => 'N/A',
                    }),
                TextEntry::make('event.title')
                    ->label('Event')
                    ->default('N/A')
                    ->hidden(fn ($record) => $record->feedback_type !== 'event'),
                TextEntry::make('task.title')
                    ->label('Task')
                    ->default('N/A')
                    ->hidden(fn ($record) => $record->feedback_type !== 'task'),
                TextEntry::make('volunteer.name')
                    ->label('Volunteer')
                    ->default('N/A')
                    ->hidden(fn ($record) => $record->feedback_type !== UserTypeEnum::VOLUNTEER->value),
                TextEntry::make('organizer.name')
                    ->label('Event Organizer')
                    ->default('N/A')
                    ->hidden(fn ($record) => $record->feedback_type !== UserTypeEnum::EVENT_ORGANIZER->value),
                TextEntry::make('manager.name')
                    ->label('Manager')
                    ->default('N/A')
                    ->hidden(fn ($record) => $record->feedback_type !== UserTypeEnum::MANAGER->value),
                TextEntry::make('comment')->label('Feedback Comment'),
                TextEntry::make('rating')->label('Rating')->default('N/A'),
                TextEntry::make('created_at')->label('Submitted On')->dateTime(),
            ]);
    }
}
