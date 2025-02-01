<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    public static function form(Forms\Form $form): Forms\Form
{
    return $form
        ->schema([
            Section::make()
                ->schema([
                    TextInput::make('name')
                    ->required()
                    ->label('Full Name'),

                TextInput::make('email')
                    ->email()
                    ->unique('users', 'email', fn ($record) => $record)
                    ->required(),

                TextInput::make('password')
                    ->password()
                    ->required()
                    ->label('Password')
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state)),

                Select::make('type')
                    ->options([
                        'Admin' => 'Admin',
                        'Volunteer' => 'Volunteer',
                        'Manager' => 'Manager',
                    ])
                    ->label('User Type')
                    ->nullable(),

                TextInput::make('phone')
                    ->tel()
                    ->label('Phone Number')
                    ->nullable(),

                DatePicker::make('date_of_birth')
                    ->label('Date of Birth')
                    ->nullable(),

                Select::make('availability')
                    ->options([
                        'Anytime' => 'Anytime',
                        'Weekdays' => 'Weekdays',
                        'Weekends' => 'Weekends',
                        'Evenings' => 'Evenings',
                    ])
                    ->default('Anytime')
                    ->label('Availability'),

                TextInput::make('languages')
                    ->label('Languages')
                    ->placeholder('e.g., English, Spanish, French')
                    ->nullable(),

                TextInput::make('emergency_contact_name')
                    ->label('Emergency Contact Name')
                    ->nullable(),

                TextInput::make('emergency_contact_phone')
                    ->tel()
                    ->label('Emergency Contact Phone')
                    ->nullable(),

                FileUpload::make('profile_picture')
                    ->label('Profile Picture')
                    ->nullable()
                    ->columnSpan(2),

                Textarea::make('skills')
                    ->label('Skills')
                    ->nullable()
                    ->columnSpan(2),

                Textarea::make('preferred_roles')
                    ->label('Preferred Roles')
                    ->nullable()
                    ->columnSpan(2),

                Textarea::make('address')
                    ->label('Address')
                    ->rows(3)
                    ->nullable()
                    ->columnSpan(2),

                Textarea::make('motivation')
                    ->label('Motivation')
                    ->rows(4)
                    ->nullable()
                    ->columnSpan(2),

                Toggle::make('is_active')
                    ->default(true)
                    ->label('Active'),
                ])->columns(2),
        ]);
}


    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                ->label('Ref'),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Name'),

                TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->label('Email'),

                TextColumn::make('phone')
                    ->label('Phone'),

                BadgeColumn::make('is_active')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'De-Active'  )
                    ->color(fn ($state) => $state ? 'success' : 'danger'),

                TextColumn::make('type'),

                TextColumn::make('availability')
                    ->label('Availability'),

                TextColumn::make('date_of_birth')
                    ->date()
                    ->label('Date of Birth'),

                TextColumn::make('preferred_roles')
                    ->label('Preferred Roles'),

                TextColumn::make('languages')
                    ->label('Languages'),

                TextColumn::make('emergency_contact_name')
                    ->label('Emergency Contact'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->icon('heroicon-o-chevron-down'),
            ])
            ->defaultSort('id','desc');
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
