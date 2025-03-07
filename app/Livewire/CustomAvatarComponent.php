<?php

namespace App\Livewire;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\View\View;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasSort;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasUser;
use Livewire\Component;

class CustomAvatarComponent extends Component implements HasForms
{
    use HasSort;
    use HasUser;
    use InteractsWithForms;

    public ?array $data = [];

    protected static int $sort = 0;

    public function mount(): void
    {
        $this->user = $this->getUser();
        $this->form->fill($this->user->only(config('filament-edit-profile.avatar_column', 'avatar_url')));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Profile Image')
                    ->description('Update your account profile image.')
                    ->columns(2)
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
            ])
            ->statePath('data');
    }

    public function updateProfile(): void
    {
        try {
            $data = $this->form->getState();

            $this->user->update($data);
        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->success()
            ->title(__('filament-edit-profile::default.saved_successfully'))
            ->send();
    }

    public function render(): View
    {
        return view('vendor.filament-edit-profile.livewire.avatar-profile-form');
    }
}
