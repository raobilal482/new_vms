<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as AuthRegister;
use Filament\Pages\Page;
use Filament\Support\Enums\VerticalAlignment;
use Illuminate\Support\Facades\Hash;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class Register extends AuthRegister
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.auth.register';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Full Name'),
                        TextInput::make('email')
                            ->email()
                            ->unique('users', 'email')
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->required()
                            ->label('Password')
                            ->confirmed()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                        TextInput::make('password_confirmation')
                            ->password()
                            ->required()
                            ->label('Confirm Password')
                            ->dehydrated(false),
                        Select::make('type')
                            ->options([
                                'Volunteer' => 'Volunteer',
                                'Manager' => 'Manager',
                                'Event Organizer' => 'Event Organizer',
                            ])
                            ->label('User Type')
                            ->required()
                            ->live(),
                        PhoneInput::make('phone')
                            ->validateFor('AUTO')
                            ->live(onBlur: true)
                            ->initialCountry('US')
                            ->label('Phone')
                            ->formatOnDisplay(true)
                            ->placeholderNumberType('FIXED_LINE')
                            ->strictMode(),
                        DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->nullable()
                            ->visible(fn ($get) => $get('type') === 'Volunteer'),
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
                            ->visible(fn ($get) => $get('type') === 'Volunteer'),
                        TextInput::make('emergency_contact_phone')
                            ->tel()
                            ->label('Emergency Contact Phone')
                            ->nullable()
                            ->visible(fn ($get) => $get('type') === 'Volunteer'),
                        FileUpload::make('profile_picture')
                            ->label('Profile Picture')
                            ->nullable()
                            ->columnSpan(2)
                            ->visible(fn ($get) => $get('type') === 'Volunteer')
                            ->directory('profile_pictures')
                            ->disk('public'),
                        Textarea::make('skills')
                            ->label('Skills')
                            ->nullable()
                            ->columnSpan(2)
                            ->visible(fn ($get) => $get('type') === 'Volunteer'),
                        Textarea::make('preferred_roles')
                            ->label('Preferred Roles')
                            ->nullable()
                            ->columnSpan(2)
                            ->visible(fn ($get) => $get('type') === 'Volunteer'),
                        Textarea::make('address')
                            ->label('Address')
                            ->rows(3)
                            ->nullable()
                            ->columnSpan(2)
                            ->visible(fn ($get) => $get('type') === 'Volunteer'),
                        Textarea::make('motivation')
                            ->label('Motivation')
                            ->rows(4)
                            ->nullable()
                            ->columnSpan(2)
                            ->visible(fn ($get) => $get('type') === 'Volunteer'),

            ])
            ->statePath('data');
    }
    public function getCachedSubNavigation(): array
    {
        return []; // No sub-navigation needed
    }
    public function getWidgetData(): array
    {
        return []; // No sub-navigation needed
    }
    public function getHeader(): array
    {
        return []; // No sub-navigation needed
    }
    public function getCachedHeaderActions(): array
    {
        return []; // No sub-navigation needed
    }
    public function getBreadcrumbs(): array
    {
        return []; // No sub-navigation needed
    }

    public function getVisibleHeaderWidgets(): array
    {
        return []; // No sub-navigation needed
    }
    public function getVisibleFooterWidgets(): array
    {
        return []; // No sub-navigation needed
    }
    public function getFooter(): array
    {
        return []; // No sub-navigation needed
    }
    public function getSubNavigationPosition(): VerticalAlignment
    {
        return VerticalAlignment::Start; // Default position, adjust if needed
    }
}
