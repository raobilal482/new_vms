<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Widgets\DashboardWidgets;
use Filament\Pages\Dashboard as PagesDashboard;

class Dashboard extends PagesDashboard
{
    public ?string $heading = 'Lettings Dashboard';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public function getTitle(): string
    {
        return $this->heading;
    }

    public function getwidgets(): array
    {
        return [
            DashboardWidgets::class,
        ];
    }

    public function getColumns(): int|string|array
    {
        return 4;
    }
}
