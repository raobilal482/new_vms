<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasSort;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasUser;
use Joaopaulolndev\FilamentEditProfile\Livewire\EditProfileForm;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Illuminate\Support\Facades\Hash;

class CustomProfileComponent extends EditProfileForm
{
    use HasSort;
    use HasUser;
    use InteractsWithForms;

    public ?array $data = [];

    public $userClass;

    protected static int $sort = 0;

    public function mount(): void
    {
        $this->user = $this->getUser();
        $this->userClass = get_class($this->user);
        $meta = $this->user->meta ?? [];
        $this->form->fill([
            'name' => $this->user->name,
            'email' => $this->user->email,
            'type' => $this->user->type,
            'phone' => $this->user->phone,
            'date_of_birth' => $this->user->date_of_birth,
            'availability' => $this->user->availability,
            'languages' => $this->user->languages,
            'emergency_contact_name' => $this->user->emergency_contact_name,
            'emergency_contact_phone' => $this->user->emergency_contact_phone,
            'profile_picture' => $this->user->profile_picture,
            'skills' => $this->user->skills,
            'preferred_roles' => $this->user->preferred_roles,
            'address' => $this->user->address,
            'motivation' => $this->user->motivation,
            'is_active' => $this->user->is_active ?? true,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('filament-edit-profile::default.profile_information'))
                    ->description(__('filament-edit-profile::default.profile_information_description'))
                    ->schema([
                        Section::make('Profile Image')
                            ->schema([
                                FileUpload::make(config('filament-edit-profile.avatar_column', 'avatar_url'))
                                    ->label(__(''))
                                    ->avatar()
                                    ->alignCenter()
                                    ->disk(config('filament-edit-profile.disk', 'public'))
                                    ->visibility(config('filament-edit-profile.visibility', 'public'))
                                    ->directory(filament('filament-edit-profile')->getAvatarDirectory())
                                    ->rules(filament('filament-edit-profile')->getAvatarRules()),
                            ]),
                            TextInput::make('name')
                            ->label(__('filament-edit-profile::default.name'))
                            ->required(),

                        TextInput::make('email')
                            ->label(__('filament-edit-profile::default.email'))
                            ->email()
                            ->required()
                            ->unique($this->userClass, ignorable: $this->user),

                        TextInput::make('password')
                            ->password()
                            ->label('Password')
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->nullable(),

                        Select::make('type')
                            ->options([
                                'Volunteer' => 'Volunteer',
                                'Manager' => 'Manager',
                                'Event Organizer' => 'Event Organizer',
                            ])
                            ->label('User Type')
                            ->required()
                            ->live()
                            ->disabled(),

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


                        Textarea::make('skills')
                            ->label('Skills')
                            ->nullable()
                            ->columnSpanFull()
                            ->visible(fn ($get) => $get('type') === 'Volunteer'),

                        Textarea::make('preferred_roles')
                            ->label('Preferred Roles')
                            ->nullable()
                            ->columnSpanFull()
                            ->visible(fn ($get) => $get('type') === 'Volunteer'),

                        Textarea::make('address')
                            ->label('Address')
                            ->rows(3)
                            ->nullable()
                            ->columnSpanFull()
                            ->visible(fn ($get) => $get('type') === 'Volunteer'),

                        Textarea::make('motivation')
                            ->label('Motivation')
                            ->rows(4)
                            ->nullable()
                            ->columnSpanFull()
                            ->visible(fn ($get) => $get('type') === 'Volunteer'),

                        Toggle::make('is_active')
                            ->default(true)
                            ->label('Active'),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Update user attributes
        $this->user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'type' => $data['type'],
            'phone' => $data['phone'],
            'date_of_birth' => $data['date_of_birth'],
            'availability' => $data['availability'],
            'languages' => $data['languages'],
            'emergency_contact_name' => $data['emergency_contact_name'],
            'emergency_contact_phone' => $data['emergency_contact_phone'],
            'profile_picture' => $data['profile_picture'],
            'skills' => $data['skills'],
            'preferred_roles' => $data['preferred_roles'],
            'address' => $data['address'],
            'motivation' => $data['motivation'],
            'is_active' => $data['is_active'],
        ]);

        // Update password if provided
        if (!empty($data['password'])) {
            $this->user->update(['password' => $data['password']]);
        }

        $this->notify('success', 'Profile updated successfully!');
    }
}
