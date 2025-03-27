<?php

namespace App\Filament\Resources;

use App\Enums\ModuleEnum;
use App\Enums\PermissionTypeEnum;
use App\Filament\Resources\RoleResource\Pages;
use App\Models\Role;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-s-rectangle-stack';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Role Information')
                    ->schema([
                        Section::make('Role Details')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->label('Role Name'),

                                TextInput::make('guard_name')
                                    ->required()
                                    ->disabled(true)
                                    ->default('web')
                                    ->maxLength(255)
                                    ->label('Guard Name')
                                    ->hidden(),

                                Section::make('Permissions')
                                    ->schema([
                                        CheckboxList::make('permissions')
                                            ->label('Permissions')
                                            ->relationship('permissions', 'name')
                                            ->searchable()
                                            ->bulkToggleable()
                                            ->columns(4),
                                    ]),
                            ])
                            ->columns(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('guard_name')
                    ->searchable()
                    ->label('Guard Name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(config('app.datetime_format'))
                    ->sortable()
                    ->label('Created At')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(config('app.datetime_format'))
                    ->sortable()
                    ->label('Updated At')
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->searchPlaceholder(function (): string {
                $all_listing = Role::count();

                return 'Search in '.$all_listing.' Roles';
            })
            ->defaultSort('id', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                ])
                    ->icon('heroicon-m-chevron-down')
                    ->color('primary'),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
