<?php

namespace App\Filament\Resources;

use App\Enums\UserTypeEnum;
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
use Spatie\Permission\Models\Role; // Import Role model
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Section::make('User Information')
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
                            ->options(UserTypeEnum::class)
                            ->label('User Type')
                            ->nullable()
                            ->live() // Ensures real-time updates
                            ->required(),

                        PhoneInput::make('phone')
                            ->validateFor('AUTO')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($livewire, $component) {
                                $livewire->validateOnly($component->getStatePath());
                            })
                            ->initialCountry('US')
                            ->label('Phone')
                            ->formatOnDisplay(true)
                            ->placeholderNumberType('FIXED_LINE')
                            ->strictMode(),

                        DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->nullable()
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),

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
                            ->nullable()
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),

                        TextInput::make('emergency_contact_phone')
                            ->tel()
                            ->label('Emergency Contact Phone')
                            ->nullable()
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),

                        FileUpload::make('profile_picture')
                            ->label('Profile Picture')
                            ->nullable()
                            ->columnSpan(2)
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),

                        Textarea::make('skills')
                            ->label('Skills')
                            ->nullable()
                            ->columnSpan(2)
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),

                        Textarea::make('preferred_roles')
                            ->label('Preferred Roles')
                            ->nullable()
                            ->columnSpan(2)
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),

                        Textarea::make('address')
                            ->label('Address')
                            ->rows(3)
                            ->nullable()
                            ->columnSpan(2)
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),

                        Textarea::make('motivation')
                            ->label('Motivation')
                            ->rows(4)
                            ->nullable()
                            ->columnSpan(2)
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),

                        Toggle::make('is_active')
                            ->default(true)
                            ->label('Active'),
                    ])
                    ->columns(2),

                // New Roles Section
                Section::make('Role Assignment')
                    ->schema([
                        Select::make('roles')
                            ->label('Assign Roles')
                            ->multiple() // Allow multiple roles
                            ->options(function () {
                                return Role::all()->pluck('name', 'name')->toArray();
                            })
                            ->preload() // Preload options for better UX
                            ->default(fn ($record) => $record ? $record->roles->pluck('name')->toArray() : [])
                            ->afterStateUpdated(function ($state, $record) {
                                if ($record) {
                                    $record->syncRoles($state);
                                }
                            })
                            ->dehydrated(false) // Prevent storing in the main form state
                            ->saveRelationshipsUsing(function ($record, $state) {
                                $record->syncRoles($state);
                            }),
                    ])
                    ->collapsible(),
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
                    ->formatStateUsing(fn ($state) => $state ? 'Active' : 'De-Active')
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
                // Optional: Display roles in the table
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->listWithLineBreaks()
                    ->limitList(3),
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
                        ->action(function (User $record, array $data) {
                            \App\Models\Feedback::create([
                                'giver_id' => auth()->id(),
                                'receiver_id' => $record->id,
                                'comment' => $data['comment'],
                                'rating' => $data['rating'],
                                'feedback_type' => $record->type,
                            ]);
                            \Filament\Notifications\Notification::make()
                                ->title('Feedback Submitted')
                                ->success()
                                ->send();
                        })
                        ->modalHeading('Provide Feedback on Event')
                        ->modalSubmitActionLabel('Submit Feedback')
                        ->modalWidth('lg'),
                ])->icon('heroicon-o-chevron-down'),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // You could add a RelationManager for roles here if preferred
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
