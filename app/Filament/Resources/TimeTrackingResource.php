<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeTrackingResource\Pages;
use App\Filament\Resources\TimeTrackingResource\RelationManagers;
use App\Models\TimeTracking;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TimeTrackingResource extends Resource
{
    protected static ?string $model = TimeTracking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public function calculateHours($checkin, $checkout, $breakIncluded, $breakMinutes) {
        if (!$checkin || !$checkout) {
            return null; // Agar check-in ya check-out missing hai toh null return karo
        }

        $checkinTime = Carbon::parse($checkin);
        $checkoutTime = Carbon::parse($checkout);
        $totalMinutes = $checkinTime->diffInMinutes($checkoutTime);

        if ($breakIncluded) {
            $totalMinutes -= $breakMinutes;
        }

        return round($totalMinutes / 60, 2); // Minutes ko hours me convert karna
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('volunteer_id')
          ,

            DateTimePicker::make('checkin_time')
                ->required()
                ->live(), // Live update

            DateTimePicker::make('checkout_time')
                ->nullable()
                ->live(), // Live update

            TextInput::make('break_duration_minutes')
                ->numeric()
                ->default(0)
                ->live(), // Live update

            Toggle::make('break_included')
                ->live(), // Live update

            TextInput::make('hours_logged')
                ->numeric()
                ->readonly()
                ->afterStateUpdated(function (Set $set, Get $get) {
                    $set('hours_logged', call_user_func(
                        [new self(), 'calculateHours'], // Yahan `$this` ki jagah `new self()` use kiya
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
                TextColumn::make('volunteer.name') // Volunteer Name
                ->label('Volunteer')
                ->sortable()
                ->searchable(),

            TextColumn::make('task.title') // Task Title
                ->label('Task')
                ->sortable()
                ->searchable(),

                TextColumn::make('checkin_time') // Check-in Time
                ->label('Check-in')
                ->sortable(),

                TextColumn::make('checkout_time') // Check-out Time
                ->label('Check-out')
                ->sortable(),

                TextColumn::make('break_included') // Break Included?
                ->label('Break?'),

            TextColumn::make('break_duration_minutes') // Break Duration (Minutes)
                ->label('Break (min)')
                ->sortable(),

            TextColumn::make('hours_logged') // Total Hours Logged
                ->label('Hours Logged')
                ->sortable(),
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
            'index' => Pages\ListTimeTrackings::route('/'),
            'create' => Pages\CreateTimeTracking::route('/create'),
            'view' => Pages\ViewTimeTracking::route('/{record}'),
            'edit' => Pages\EditTimeTracking::route('/{record}/edit'),
        ];
    }
}
