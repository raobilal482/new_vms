<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Widgets\DashboardWidgets;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as PagesDashboard;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget;

class Dashboard extends BaseDashboard
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
    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        DatePicker::make('startDate'),
                        DatePicker::make('endDate'),
                        // ...
                    ])
                    ->columns(3),
            ]);
    }
}
