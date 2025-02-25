<?php

namespace App\Filament\Pages\Widgets;

use App\Models\Certificate;
use App\Models\Event;
use App\Models\Listing;
use App\Models\Tenancy;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardWidgets extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // $isSuperAdmin = auth()->user()->is_super_admin;

        // // Expired Certificates
        // $expiredCertificates = Certificate::query()
        //     ->when(! $isSuperAdmin, function ($query) {
        //         $query->where('company_id', auth()->user()->company_id);
        //     })
        //     ->isExpired()
        //     ->count();

        // // Missing Certificates
        // $missingCount = Certificate::query()
        //     ->when(! $isSuperAdmin, function ($query) {
        //         $query->where('company_id', auth()->user()->company_id);
        //     })
        //     ->isMissing()
        //     ->count();

        // // Tenancies
        // $depositTorRegister = Tenancy::query()
        //     ->when(! $isSuperAdmin, function ($query) {
        //         $query->where('company_id', auth()->user()->company_id);
        //     })
        //     ->toBeRegistered()
        //     ->count();

        // $incorrectTenancies = Tenancy::query()
        //     ->when(! $isSuperAdmin, function ($query) {
        //         $query->where('company_id', auth()->user()->company_id);
        //     })
        //     ->incorrect()
        //     ->count();

        // Units
        $events = Event::count();

        return [
            Stat::make('Events', $events ?? 0)
                ->description('Click to view Events')
                ->descriptionIcon('heroicon-s-eye')
                ->color('warning')
                ->url('#'),
            // Stat::make('Missing Certificates', $missingCount ?? 0)
            //     ->description('Click to view Certificates')
            //     ->descriptionIcon('heroicon-m-eye')
            //     ->color('warning')
            //     // ->chart([7, 2, 10, 3, 15, 4, 17])
            //     ->url('/certificates?tableFilters[Certificatefilter][certificate_status]=Missing'),

            // Stat::make('Deposit to Register', $depositTorRegister)
            //     ->description('Click to view Tenancies')
            //     ->descriptionIcon('heroicon-o-eye')
            //     ->color('danger')
            //     ->url('/tenancies?tableFilters[to_be_registered][value]=1'),

            // Stat::make('Incorrect Tenancy Status', $incorrectTenancies)
            //     ->description('Click to view Tenancies')
            //     ->descriptionIcon('heroicon-m-eye')
            //     ->color('danger')
            //     ->url('/tenancies?tableFilters[to_be_registered][value]=null&tableFilters[status][incorrect_tenancies]=true'),

            // Stat::make('Vacant Units', $vacantUnits)
            //     ->color('success')
            //     ->description('Click to view Units')
            //     ->descriptionIcon('heroicon-o-eye')
            //     ->color('danger')
            //     ->url('/units'),

        ];
    }
}
