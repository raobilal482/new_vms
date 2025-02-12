<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Models\TimeTracking;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function afterCreate(): void
    {
        $task = $this->record;

        $assignedVolunteers = $task->volunteers;

        foreach ($assignedVolunteers as $volunteer) {
            TimeTracking::create([
                'volunteer_id' => $volunteer->id,
                'task_id' => $task->id,
                'checkin_time' => null,
                'checkout_time' => null,
                'break_included' => false,
                'break_duration_minutes' => 0,
                'hours_logged' => 0,
            ]);
        }
    }
}
