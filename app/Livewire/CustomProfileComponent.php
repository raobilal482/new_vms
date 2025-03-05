<?php

// namespace App\Livewire;

// use Livewire\Component;

// class CustomProfileComponent extends Component
// {
//     public function render()
//     {
//         return view('livewire.custom-profile-component');
//     }
// }
namespace App\Livewire;
use Livewire\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasSort;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasUser;
use Joaopaulolndev\FilamentEditProfile\Livewire\EditProfileForm;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Illuminate\Support\Arr;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

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
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('filament-edit-profile::default.profile_information'))
                    ->description(__('filament-edit-profile::default.profile_information_description'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('First Name'))
                            ->required(),

                        TextInput::make('email')
                            ->label(__('filament-edit-profile::default.email'))
                            ->email()
                            ->columnSpanFull()
                            ->required()
                            ->unique($this->userClass, ignorable: $this->user),
                      

                    ])->columns(2),

            ])
            ->statePath('data');
    }
}
