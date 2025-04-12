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
use Illuminate\Support\Facades\Auth;

class DashboardWidgets extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        $stats = [];

        // Base stats for all users (will be filtered later)
        $totalEventsQuery = Event::query();
        $approvedEventsQuery = Event::where('is_approved', 'Approved');
        $notApprovedEventsQuery = Event::where('is_approved', 'Pending');
        $totalTasksQuery = Task::query();
        $totalVolunteers = User::where('type', UserTypeEnum::VOLUNTEER->value)->count();
        $totalEventOrganizers = User::where('type', UserTypeEnum::EVENT_ORGANIZER->value)->count();
        $totalManagers = User::where('type', UserTypeEnum::MANAGER->value)->count();

        // For Event Organizers, filter events and tasks by their user ID
        if ($user->type === UserTypeEnum::EVENT_ORGANIZER->value) {
            $totalEventsQuery->where('created_by', $user->id);
            $approvedEventsQuery->where('created_by', $user->id);
            $notApprovedEventsQuery->where('created_by', $user->id);
            $totalTasksQuery->where('created_by', $user->id);
        }

        // Calculate counts
        $totalEvents = $totalEventsQuery->count();
        $approvedEvents = $approvedEventsQuery->count();
        $notApprovedEvents = $notApprovedEventsQuery->count();
        $totalTasks = $totalTasksQuery->count();

        // Common stats for all users
        $stats[] = Stat::make('Total Events', $totalEvents ?? 0);
        $stats[] = Stat::make('Approved Events', $approvedEvents ?? 0);
        $stats[] = Stat::make('Total Tasks', $totalTasks ?? 0);
        $stats[] = Stat::make('All Volunteers', $totalVolunteers ?? 0);
        $stats[] = Stat::make('All Event Organizers', $totalEventOrganizers ?? 0);
        $stats[] = Stat::make('All Managers', $totalManagers ?? 0);

        // Add "Not Approved Events" only for Admins and Event Organizers
        if ($user->type !== UserTypeEnum::VOLUNTEER->value) {
            $stats[] = Stat::make('Not Approved Events', $notApprovedEvents ?? 0);
        }

        // For Admins, ensure all stats are included (already handled by default queries)
        // No additional logic needed since queries are unfiltered for Admins

        return $stats;
    }
}
