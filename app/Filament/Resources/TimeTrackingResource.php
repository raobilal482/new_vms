<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeTrackingResource\Pages;
use App\Filament\Resources\TimeTrackingResource\RelationManagers;
use App\Models\TimeTracking;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TimeTrackingResource extends Resource
{
    protected static ?string $model = TimeTracking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('volunteer_id'),

            DateTimePicker::make('checkin_time')
                ->required()
                ->live(),

            DateTimePicker::make('checkout_time')
                ->nullable()
                ->live(),

            TextInput::make('break_duration_minutes')
                ->numeric()
                ->default(0)
                ->live(),

            Toggle::make('break_included')
                ->live(),

            TextInput::make('hours_logged')
                ->numeric()
                ->readonly()
                ->afterStateHydrated(function (Set $set, Get $get) {
                    $set('hours_logged', self::calculateHours(
                        $get('checkin_time'),
                        $get('checkout_time'),
                        $get('break_included'),
                        $get('break_duration_minutes')
                    ));
                }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('volunteer.name')
                    ->label('Volunteer')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('task.title')
                    ->label('Task')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('checkin_time')
                    ->label('Check-in')
                    ->sortable(),
                TextColumn::make('checkout_time')
                    ->label('Check-out')
                    ->sortable(),
                TextColumn::make('break_included')
                    ->label('Break Included')
                    ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                    ->sortable()
                    ->badge(),
                TextColumn::make('break_duration_minutes')
                    ->label('Break (min)')
                    ->sortable(),
                TextColumn::make('hours_logged')
                    ->label('Hours Logged')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state !== null ? number_format($state, 2) . ' Hours' : 'N/A'),
                // Virtual Status Column
                TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function (TimeTracking $record) {
                        if (!$record->checkin_time && !$record->checkout_time) {
                            return 'Pending';
                        } elseif ($record->checkin_time && !$record->checkout_time) {
                            return 'In Progress';
                        } else {
                            return 'Completed';
                        }
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'gray',
                        'In Progress' => 'warning',
                        'Completed' => 'success',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    // Check In Action
                    Tables\Actions\Action::make('check_in')
                        ->label('Check In')
                        ->icon('heroicon-o-clock')
                        ->visible(fn (TimeTracking $record) => !$record->checkin_time)
                        ->form([
                            DateTimePicker::make('checkin_time')
                                ->required()
                                ->default(now())
                                ->label('Check-in Time'),
                        ])
                        ->action(function (TimeTracking $record, array $data) {
                            $record->update([
                                'checkin_time' => $data['checkin_time'],
                            ]);
                            $record->task->update([
                                'status' => 'in progress',
                            ]);
                        }),

                    // Check Out Action
                    Tables\Actions\Action::make('check_out')
                        ->label('Check Out')
                        ->icon('heroicon-o-clock')
                        ->visible(fn (TimeTracking $record) => $record->checkin_time && !$record->checkout_time)
                        ->form([
                            DateTimePicker::make('checkout_time')
                                ->required()
                                ->default(now())
                                ->label('Check-out Time')
                                ->rules(['after:record.checkin_time'])
                                ->validationMessages([
                                    'after' => 'Checkout time must be after check-in time (:record.checkin_time).',
                                ]),
                            TextInput::make('break_duration_minutes')
                                ->numeric()
                                ->default(0)
                                ->label('Break Duration (minutes)')
                                ->minValue(0),
                            Toggle::make('break_included')
                                ->label('Include Break?'),
                        ])
                        ->action(function (TimeTracking $record, array $data) {
                            $hours = self::calculateHours(
                                $record->checkin_time,
                                $data['checkout_time'],
                                $data['break_included'],
                                $data['break_duration_minutes']
                            );

                            $record->update([
                                'checkout_time' => $data['checkout_time'],
                                'break_duration_minutes' => $data['break_duration_minutes'],
                                'break_included' => $data['break_included'],
                                'hours_logged' => $hours,
                            ]);
                            $record->task->update([
                                'status' => 'completed',
                            ]);
                        }),
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
            'index' => Pages\ListTimeTrackings::route('/'),
            'create' => Pages\CreateTimeTracking::route('/create'),
            'view' => Pages\ViewTimeTracking::route('/{record}'),
            'edit' => Pages\EditTimeTracking::route('/{record}/edit'),
        ];
    }
}
