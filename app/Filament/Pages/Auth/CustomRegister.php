<?php

namespace App\Filament\Pages\Auth;

use App\Enums\UserTypeEnum;
use App\Models\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Support\Facades\Hash;

class CustomRegister extends BaseRegister
{


    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getRoleFormComponent(),
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
            'password' => $data['password'],
            'type' => $data['type'], // Assuming your User model has a 'type' column
        ]);

        // Check if a role exists matching the selected type and assign it
        $roleName = strtolower($data['type']); // e.g., "volunteer" or "event_organizer"
        $role = Role::where('name', $roleName)->first();

        if ($role) {
            $user->assignRole($role);
        }

        return $user;
    }
}
