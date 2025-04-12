<?php

namespace App\Filament\Resources\TimeTrackingResource\Pages;

use App\Filament\Resources\TimeTrackingResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewTimeTracking extends ViewRecord
{
    protected static string $resource = TimeTrackingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    public  function infolist(Infolist $infolist): Infolist
{
    return $infolist->schema([
        Section::make()
        ->schema([
            TextEntry::make('volunteer_id')
            ->label('Volunteer ID'),

        TextEntry::make('checkin_time')
            ->label('Check-in Time')
            ->dateTime()
            ->default('N/A'), // Optional: Display for null values

        TextEntry::make('checkout_time')
            ->label('Check-out Time')
            ->dateTime()
            ->default('N/A'), // Optional: Display for null values

        TextEntry::make('break_duration_minutes')
            ->label('Break Duration (Minutes)')
            ->formatStateUsing(fn ($state) => $state ?? 0),

        TextEntry::make('break_included')
            ->label('Break Included'),

        TextEntry::make('hours_logged')
            ->label('Hours Logged')
            ->numeric()
            ->getStateUsing(function ($record) {
                return self::calculateHours(
                    $record->checkin_time,
                    $record->checkout_time,
                    $record->break_included,
                    $record->break_duration_minutes
                );
            }),
        ])->columns(2),

    ]);
}
public static function calculateHours($checkin, $checkout, $breakIncluded, $breakMinutes)
    {
        if (!$checkin || !$checkout) {
            return null;
        }

        $checkinTime = Carbon::parse($checkin);
        $checkoutTime = Carbon::parse($checkout);

        if ($checkoutTime->lessThan($checkinTime)) {
            return 0; // Prevent negative hours
        }

        $totalSeconds = $checkinTime->diffInSeconds($checkoutTime);
        $breakSeconds = $breakIncluded && $breakMinutes > 0 ? $breakMinutes * 60 : 0;
        $workedSeconds = max(0, $totalSeconds - $breakSeconds);

        return round($workedSeconds / 3600, 2); // 3600 seconds = 1 hour
    }
}
