<?php

namespace App\Livewire;

use App\Enums\UserTypeEnum;
use Livewire\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
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
            'profile_picture' => $this->user->getFirstMedia('profile_picture') ? $this->user->getFirstMedia('profile_picture')->getUrl() : null,
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
                        SpatieMediaLibraryFileUpload::make('profile_picture')
                            ->label('Profile Picture')
                            ->collection('profile_picture')
                            ->preserveFilenames()
                            ->image()
                            ->maxFiles(1),

                        TextInput::make('name')
                            ->required()
                            ->label('Full Name'),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->disabled(),

                        Select::make('type')
                            ->options(UserTypeEnum::class)
                            ->label('User Type')
                            ->disabled()
                            ->visible(fn () => auth()->user()->type),

                        PhoneInput::make('phone')
                            ->label('Phone')
                            ->initialCountry('LT')
                            ->default('+3706')
                            ->nullable(),

                        DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->required()
                            ->visible(fn () => auth()->user()->type === UserTypeEnum::VOLUNTEER->value),

                        Select::make('availability')
                            ->options([
                                'Anytime' => 'Anytime',
                                'Weekdays' => 'Weekdays',
                                'Weekends' => 'Weekends',
                                'Evenings' => 'Evenings',
                            ])
                            ->default('Anytime')
                            ->label('Availability')
                            ->visible(fn () => auth()->user()->type === UserTypeEnum::VOLUNTEER->value),

                        TextInput::make('languages')
                            ->label('Languages')
                            ->placeholder('e.g., English, Spanish, French')
                            ->nullable()
                            ->visible(fn () => auth()->user()->type === UserTypeEnum::VOLUNTEER->value),

                        TextInput::make('emergency_contact_name')
                            ->label('Emergency Contact Name')
                            ->nullable()
                            ->visible(fn () => auth()->user()->type === UserTypeEnum::VOLUNTEER->value),

                        PhoneInput::make('emergency_contact_phone')
                            ->label('Emergency Contact Phone')
                            ->initialCountry('LT')
                            ->default('+3706')
                            ->nullable()
                            ->visible(fn () => auth()->user()->type === UserTypeEnum::VOLUNTEER->value),

                        Textarea::make('skills')
                            ->label('Skills')
                            ->nullable()
                            ->visible(fn () => auth()->user()->type === UserTypeEnum::VOLUNTEER->value),

                        Textarea::make('preferred_roles')
                            ->label('Preferred Roles')
                            ->nullable()
                            ->visible(fn () => auth()->user()->type === UserTypeEnum::VOLUNTEER->value),

                        Textarea::make('address')
                            ->label('Address')
                            ->rows(3)
                            ->nullable()
                            ->visible(fn () => auth()->user()->type === UserTypeEnum::VOLUNTEER->value),

                        Textarea::make('motivation')
                            ->label('Motivation')
                            ->rows(4)
                            ->nullable()
                            ->visible(fn () => auth()->user()->type === UserTypeEnum::VOLUNTEER->value),

                        Toggle::make('is_active')
                            ->default(true)
                            ->label('Active')
                            ->visible(fn () => auth()->user()->type === UserTypeEnum::VOLUNTEER->value),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $profilePictureState = $this->form->getComponent('profile_picture')->getState();
        $this->user->update([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'availability' => $data['availability'] ?? null,
            'languages' => $data['languages'] ?? null,
            'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
            'skills' => $data['skills'] ?? null,
            'preferred_roles' => $data['preferred_roles'] ?? null,
            'address' => $data['address'] ?? null,
            'motivation' => $data['motivation'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        // Handle SpatieMediaLibraryFileUpload
        if ($profilePictureState) {
            // Clear existing media only if a new file is uploaded
            $this->user->clearMediaCollection('profile_picture');

            if (is_array($profilePictureState)) {
                foreach ($profilePictureState as $file) {
                    if ($file instanceof TemporaryUploadedFile) {
                        $this->user->addMedia($file->getRealPath())
                                   ->usingFileName($file->getClientOriginalName())
                                   ->toMediaCollection('profile_picture');
                    }
                }
            } elseif (is_string($profilePictureState)) {
                // If the state is a string, it might be a temporary path
                $this->user->addMedia($profilePictureState)
                           ->toMediaCollection('profile_picture');
            } elseif ($profilePictureState instanceof TemporaryUploadedFile) {
                $this->user->addMedia($profilePictureState->getRealPath())
                           ->usingFileName($profilePictureState->getClientOriginalName())
                           ->toMediaCollection('profile_picture');
            }
        }

        $this->notify('success', __('filament-edit-profile::default.profile_updated'));
    }
}
