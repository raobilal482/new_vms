<?php

namespace App\Filament\Resources;

use AbanoubNassem\FilamentPhoneField\Forms\Components\PhoneInput;
use App\Enums\UserTypeEnum;
use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-s-calendar';
    protected static ?string $navigationLabel = 'Events';
    protected static ?string $pluralLabel = 'Events';
    protected static ?string $slug = 'events';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Event Details')
                ->schema([
                    TextInput::make('title')
                        ->label('Event Title')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('description')
                        ->label('Description')
                        ->maxLength(1000),
                    TextInput::make('location')
                        ->label('Location')
                        ->required()
                        ->maxLength(255),
                    Flatpickr::make('start_time')
                            ->label('Start Time')
                            ->required()
                            ->dateFormat('Y-m-d'),
                    Flatpickr::make('end_time')
                        ->label('End Time')
                        ->required()
                        ->dateFormat('Y-m-d'),
                    TextInput::make('max_volunteers')
                        ->label('Max Volunteers')
                        ->numeric()
                        ->required(),
                    Select::make('type')
                        ->label('Event Type')
                        ->options([
                            'General' => 'General',
                            'Fundraiser' => 'Fundraiser',
                            'Workshop' => 'Workshop',
                        ])
                        ->default('General')
                        ->required(),
                    Select::make('status')
                        ->label('Event Status')
                        ->options([
                            'Upcoming' => 'Upcoming',
                            'Completed' => 'Completed',
                            'Cancelled' => 'Cancelled',
                        ])
                        ->default('Upcoming')
                        ->required(),
                    Select::make('event_organizer_id')
                        ->label('Event Organizer')
                        ->relationship('event_organizer', 'name')
                        ->nullable(),
                    Select::make('manager_id')
                        ->label('Manager')
                        ->relationship('manager', 'name')
                        ->nullable(),
                        Select::make('volunteer_id') // Note: Should match the relationship name 'volunteers'
                        ->label('Volunteers')
                        ->relationship('volunteers', 'name') // Use 'volunteers' not 'volunteer_id'
                        ->multiple() // Since it's a many-to-many relationship
                        ->searchable()
                        ->preload()
                        ->nullable(),
                    Checkbox::make('is_virtual')
                        ->label('Is Virtual?'),
                    TextInput::make('platform_link')
                        ->label('Platform Link')
                        ->nullable()
                        ->url(),
                    Textarea::make('requirements')
                        ->label('Event Requirements')
                        ->nullable(),
                    TextInput::make('contact_email')
                        ->label('Contact Email')
                        ->nullable()
                        ->email(),
                    PhoneInput::make('contact_phone')
                        ->initialCountry('LT') // Set initial country to Lithuania
                        ->default('+3706')     // Prefill with +3706
                        ->tel()                // Enable phone input functionality
                        ->mask(fn (TextInput\Mask $mask) => $mask
                            ->pattern('+3706{0000000}') // Enforce +3706 followed by exactly 7 digits
                            ->numeric()                  // Restrict input to numbers only
                        )
                        ->nullable()
                        ,
                    Textarea::make('tags')
                        ->label('Tags')
                        ->nullable(),
                    TextInput::make('duration')
                        ->label('Event Duration (in minutes)')
                        ->numeric()
                        ->nullable(),
                    Checkbox::make('whitelist')
                        ->label('Whitelist')
                        ->visible(fn () => Auth::user()->can('event.whitelist-action')),
                ])
                ->columns(2),
        ]);
    }
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
        ->modifyQueryUsing(function (Builder $query) {
            $user = auth()->user();
            if ($user->type === 'Admin' || $user->hasRole('Admin')) {
                $query->where('whitelist', false);
                return $query;
            }
            if($user->type !== 'Admin') {
                return $query->where(function (Builder $query) use ($user) {
                    $query->where('created_by', $user->id)
                          ->where(function ($query) {
                              $query->where('is_approved', 'Approved')
                                    ->orWhereNull('is_approved');
                          })
                          ->orWhere('whitelist', true)
                          ->orWhere('is_approved', 'Rejected');
                });
            }
            if($user->type == UserTypeEnum::VOLUNTEER->value) {
                return $query->where('is_approved', 'Approved');
            }
            if($user->type == UserTypeEnum::MANAGER->value) {
                return $query->where('is_approved', 'Pending');
            }
            return $query;

        })
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->label('Event Title')
                    ->wrap(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->label('Description')
                    ->wrap(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Location')
                    ->wrap(),
                Tables\Columns\TextColumn::make('start_time')
                    ->dateTime('M d, Y H:i')
                    ->label('Start Time'),
                Tables\Columns\TextColumn::make('end_time')
                    ->dateTime('M d, Y H:i')
                    ->label('End Time'),
                Tables\Columns\TextColumn::make('max_volunteers')
                    ->label('Max Volunteers'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Event Type'),
                Tables\Columns\BooleanColumn::make('is_virtual')
                    ->label('Is Virtual'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status'),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration (min)'),
                Tables\Columns\TextColumn::make('tags')
                    ->label('Tags'),
                Tables\Columns\TextColumn::make('is_approved')
                    ->label('Approval Status')
                    ->formatStateUsing(function ($record, $state){
                        if($record->whitelist) {
                            return "Whitelist";
                        }
                        return $state ?? 'Pending';
                    }) // Display "Pending" if NULL or empty
                    ->default('Pending')
                    ->badge()
                    ->color(fn ($state) => match ($state ?? 'Pending') {
                        'Pending' => 'warning',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Upcoming' => 'Upcoming',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->label('Event Status'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->visible(fn (Event $record) => $record->created_by == auth()->user()->id),
                    Tables\Actions\DeleteAction::make()
                    ->visible(fn (Event $record) => $record->created_by == auth()->user()->id),
                    Tables\Actions\Action::make('manage_approval')
                        ->label('Manage Approval')
                        ->icon('heroicon-o-check-circle')
                        ->visible(fn (Event $record) => auth()->user()->type === 'Admin' && $record->is_approved !== 'Approved' || auth()->user()->type === UserTypeEnum::MANAGER->value && $record->is_approved !== 'Approved') // Hide if Approved
                        ->form([
                            Select::make('action')
                                ->label('Action')
                                ->options([
                                    'approve' => 'Approve',
                                    'reject' => 'Reject',
                                ])
                                ->required()
                                ->live(), // Real-time updates for conditional fields
                            Textarea::make('reason')
                                ->label('Reason (Required for Reject)')
                                ->required(fn ($get) => $get('action') === 'reject') // Required only for reject
                                ->hidden(fn ($get) => $get('action') === 'approve' || !$get('action')) // Hidden for approve
                                ->maxLength(500),
                        ])
                        ->action(function (Event $record, array $data) {
                            if ($data['action'] === 'approve') {
                                $record->update(['is_approved' => 'Approved']);
                                \Filament\Notifications\Notification::make()
                                    ->title('Event Approved')
                                    ->success()
                                    ->send();
                            } elseif ($data['action'] === 'reject') {
                                $record->update([
                                    'is_approved' => 'Rejected',
                                    'rejection_reason' => $data['reason'],
                                ]);
                                \Filament\Notifications\Notification::make()
                                    ->title('Event Rejected')
                                    ->body('Reason: ' . $data['reason'])
                                    ->warning()
                                    ->send();
                            }
                        })
                        ->modalHeading('Manage Event Approval')
                        ->modalSubmitActionLabel('Confirm')
                        ->modalWidth('sm'),
                    Tables\Actions\Action::make('give_feedback')
                        ->label('Give Feedback')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->form([
                            Textarea::make('comment')
                                ->label('Feedback')
                                ->required()
                                ->maxLength(500)
                                ->columnSpanFull(),
                            Select::make('rating')
                                ->label('Rating')
                                ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'])
                                ->nullable(),
                        ])
                        ->action(function (Event $record, array $data) {
                            \App\Models\Feedback::create([
                                'giver_id' => auth()->id(),
                                'event_id' => $record->id,
                                'comment' => $data['comment'],
                                'rating' => $data['rating'],
                                'feedback_type' => 'event',
                            ]);
                            \Filament\Notifications\Notification::make()
                                ->title('Feedback Submitted')
                                ->success()
                                ->send();
                        })
                        ->modalHeading('Provide Feedback on Event')
                        ->modalSubmitActionLabel('Submit Feedback')
                        ->modalWidth('lg'),


                    Tables\Actions\Action::make('whilelist')
                    ->label('Apply for Approval')
                    ->visible(fn () => Auth::user()->type === UserTypeEnum::EVENT_ORGANIZER->value) // Only visible to volunteers
                    ->requiresConfirmation() // Shows a confirmation modal
                    ->modalHeading('Apply for Approval')
                    ->modalDescription('Do you want to apply this event for approval.')
                    ->modalSubmitActionLabel('Yes, Apply')
                    ->modalCancelActionLabel('No, Cancel')
                    ->action(function (Event $record) {
                        $volunteer = Auth::user();

                        // Create TimeTracking record
                        $record->update([
                            'whitelist' => false,
                            'is_approved' =>'Pending',
                        ]);

                    })
                    ->color('success'),

                ])
                ->label('Actions')
                ->icon('heroicon-o-chevron-down'),
            ])
            ->headerActions([])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'view' => Pages\ViewEvent::route('/{record}'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
