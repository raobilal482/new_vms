<?php

namespace App\Filament\Pages\Widgets;

use App\Enums\UserTypeEnum;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\Listing;
use App\Models\Task;
use App\Models\Tenancy;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardWidgets extends StatsOverviewWidget
{
    protected function getStats(): array
    {

        $TotalEvents = Event::count();

        $ApprovedEvents = Event::where('is_approved', 'Approved')->count();

        $NotApprovedEvents = Event::where('is_approved', 'Pending')->count();

        $TotalTasks = Task::count();

        $totalVolunteers = User::where('type',UserTypeEnum::VOLUNTEER->value)->count();

        $totalEventOrganizers = User::where('type',UserTypeEnum::EVENT_ORGANIZER->value)->count();

        $totalManagers = User::where('type',UserTypeEnum::MANAGER->value)->count();



        return [
            Stat::make('Total Events', $TotalEvents ?? 0),

            Stat::make('Approved Events', $ApprovedEvents ?? 0),

            Stat::make('Not Approved Events', $NotApprovedEvents ?? 0),

            Stat::make('Total Tasks', $TotalTasks ?? 0),

            Stat::make('All Volunteers', $totalVolunteers ?? 0),

            Stat::make('All Event Organizers', $totalEventOrganizers ?? 0),

            Stat::make('All Managers', $totalManagers ?? 0),


        ];
    }
}
