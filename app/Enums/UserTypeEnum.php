<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserTypeEnum: string implements HasLabel
{
    case VOLUNTEER = 'Volunteer';
    case EVENT_ORGANIZER = 'Event Organizer';
    case MANAGER = 'Manager';
    case ADMIN = 'Admin';

    public function getLabel(): ?string
    {
        return $this->value;
    }

    public static function toArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->value] = $case->value;
        }

        return $array;
    }

    public static function getRandomValue(): string
    {
        $values = self::toArray();

        return $values[array_rand($values)];
    }
}
