<?php

namespace App\Filament\Resources\FeedbackResource\Pages;

use App\Filament\Resources\FeedbackResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFeedback extends CreateRecord
{
    protected static string $resource = FeedbackResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $data['giver_id'] = auth()->id();

        return $data;
    }
    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}

}
