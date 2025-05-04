<?php

namespace App\Filament\Pages\Auth;

use AbanoubNassem\FilamentPhoneField\Forms\Components\PhoneInput as ComponentsPhoneInput;
use App\Enums\UserTypeEnum;
use App\Models\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\PhoneInput;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Hash;

class CustomRegister extends BaseRegister
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        Select::make('type')
                            ->options([
                                UserTypeEnum::VOLUNTEER->value => UserTypeEnum::VOLUNTEER->value,
                                UserTypeEnum::EVENT_ORGANIZER->value => UserTypeEnum::EVENT_ORGANIZER->value,
                            ])
                            ->default(UserTypeEnum::VOLUNTEER->value)
                            ->required()
                            ->live(),

                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),

                        // Volunteer-specific fields
                        DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->required()
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),

                        Select::make('availability')
                            ->options([
                                'Anytime' => 'Anytime',
                                'Weekdays' => 'Weekdays',
                                'Weekends' => 'Weekends',
                                'Evenings' => 'Evenings',
                            ])
                            ->default('Anytime')
                            ->label('Availability')
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),

                        TextInput::make('languages')
                            ->label('Languages')
                            ->placeholder('e.g., English, Spanish, French')
                            ->nullable()
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),

                        TextInput::make('emergency_contact_name')
                            ->label('Emergency Contact Name')
                            ->nullable()
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),

                        ComponentsPhoneInput::make('emergency_contact_phone')
                            ->label('Emergency Contact Phone')
                            ->tel()
                            ->initialCountry('LT')
                            ->default('+3706')
                            ->mask(fn (TextInput\Mask $mask) => $mask
                                ->pattern('+3706{0000000}')
                                ->numeric()
                            )
                            ->nullable()
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),

                            SpatieMediaLibraryFileUpload::make('profile_picture')
                            ->label('Profile Picture')
                            ->collection('profile_picture')
                            ->preserveFilenames()
                            ->image()
                            ->maxFiles(1) // Match with singleFile() in the model
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),
                        Textarea::make('skills')
                            ->label('Skills')
                            ->nullable()
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),

                        Textarea::make('preferred_roles')
                            ->label('Preferred Roles')
                            ->nullable()
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),

                        Textarea::make('address')
                            ->label('Address')
                            ->rows(3)
                            ->nullable()
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),

                        Textarea::make('motivation')
                            ->label('Motivation')
                            ->rows(4)
                            ->nullable()
                            ->visible(fn ($get) => $get('type') === UserTypeEnum::VOLUNTEER->value),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getRoleFormComponent(): Component
    {
        return Select::make('type')
            ->options([
                UserTypeEnum::VOLUNTEER->value => UserTypeEnum::VOLUNTEER->value,
                UserTypeEnum::EVENT_ORGANIZER->value => UserTypeEnum::EVENT_ORGANIZER->value,
            ])
            ->default(UserTypeEnum::VOLUNTEER->value)
            ->required();
    }

    protected function handleRegistration(array $data): \Illuminate\Foundation\Auth\User
    {
        // Create the user
        $user = $this->getUserModel()::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'type' => $data['type'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'availability' => $data['availability'] ?? null,
            'languages' => $data['languages'] ?? null,
            'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
            'profile_picture' => $data['profile_picture'] ?? null,
            'skills' => $data['skills'] ?? null,
            'preferred_roles' => $data['preferred_roles'] ?? null,
            'address' => $data['address'] ?? null,
            'motivation' => $data['motivation'] ?? null,
        ]);

        // Check if a role exists matching the selected type and assign it
        $roleName = strtolower($data['type']);
        $role = Role::where('name', $roleName)->first();

        if ($role) {
            $user->assignRole($role);
        }

        return $user;
    }
}
