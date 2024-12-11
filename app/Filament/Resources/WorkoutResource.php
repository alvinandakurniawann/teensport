<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkoutResource\Pages;
use App\Models\Workout;
use App\Models\Exercise;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;

class WorkoutResource extends Resource
{
    protected static ?string $model = Workout::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Workout Management';
    protected static ?int $navigationSort = 3;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable(),
                        Forms\Components\Select::make('type')
                            ->options([
                                'pull_day' => 'Pull Day',
                                'push_day' => 'Push Day',
                                'leg_day' => 'Leg Day',
                            ])
                            ->required(),
                        Forms\Components\Select::make('difficulty_level')
                            ->options([
                                'beginner' => 'Beginner',
                                'intermediate' => 'Intermediate',
                                'advanced' => 'Advanced',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Repeater::make('workout_exercises')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('exercise_id')
                                    ->label('Exercise')
                                    ->options(Exercise::pluck('name', 'id'))
                                    ->required()
                                    ->searchable(),
                                Forms\Components\TextInput::make('sets')
                                    ->numeric()
                                    ->default(3)
                                    ->required(),
                                Forms\Components\TextInput::make('reps')
                                    ->numeric()
                                    ->default(12)
                                    ->required(),
                                Forms\Components\TextInput::make('duration')
                                    ->numeric()
                                    ->label('Duration (seconds)')
                                    ->nullable(),
                            ])
                            ->columns(4)
                            ->defaultItems(1)
                            ->createItemButtonLabel('Add Exercise')
                    ])
            ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('exercises')
                ->multiple()
                ->relationship('exercises', 'name')
                ->preload()
                ->required(),
        ];
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('difficulty_level')
                    ->sortable(),
                Tables\Columns\TextColumn::make('exercises_count')
                    ->counts('exercises')
                    ->label('Exercises'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Fix: Change relationship filter to use a callback for proper joining
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'pull_day' => 'Pull Day',
                        'push_day' => 'Push Day',
                        'leg_day' => 'Leg Day',
                    ]),
                Tables\Filters\SelectFilter::make('difficulty_level')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkouts::route('/'),
            'create' => Pages\CreateWorkout::route('/create'),
            'edit' => Pages\EditWorkout::route('/{record}/edit'),
        ];
    }
}