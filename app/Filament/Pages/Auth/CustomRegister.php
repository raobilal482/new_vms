<?php

namespace App\Filament\Pages\Auth;

use App\Enums\UserTypeEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;
use Filament\Pages\Auth\Register as BaseRegister;

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
}
