<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TurmaResource\Pages;
use App\Filament\Resources\TurmaResource\RelationManagers;
use App\Models\Turma;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TurmaResource extends Resource
{
    protected static ?string $model = Turma::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = "Gestão Escolar";

    public static ?string $label = 'Turma';

    public static ?string $pluralLabel = 'Turmas';

    public static ?string $slug = 'turmas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('curso_id')
                    ->label('Curso')
                    ->relationship('curso', 'nome')
                    ->required(),
                Forms\Components\Select::make('periodo_id')
                    ->label('Período')
                    ->searchable()
                    ->preload()
                    ->relationship('periodo', 'nome')
                    ->required(),
                Forms\Components\Select::make('turno_id')
                    ->label('Turno')
                    ->relationship('turno', 'nome')
                    ->required(),
                Forms\Components\TextInput::make('sigla')
                    ->label('Sigla')
                    ->helperText('A sigla é para dividir as turnas entre turma A, B, C, etc.')
                    ->required(),
                Forms\Components\DatePicker::make('ano')
                    ->label('Ano')
                    ->displayFormat('Y')
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'Concluido' => 'Concluido',
                        'Cursando' => 'Cursando',
                        'Inativo' => 'Inativo',
                    ])
                    ->required()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('curso.nome')
                    ->label('Curso')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('periodo.nome')
                    ->label('Período')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sigla')
                    ->label('Sigla')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('turno.nome')
                    ->label('Turno')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ano')
                    ->label('Ano')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->options([
                        'Concluido' => 'Concluido',
                        'Cursando' => 'Cursando',
                        'Inativo' => 'Inativo',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();

                            // Se não estiver autenticado, esconde
                            if (!$user) {
                                return false;
                            }

                            // Mostra só para Admin
                            return $user->hasRole('Admin');
                        }),
                ]),
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
            'index' => Pages\ListTurmas::route('/'),
            'create' => Pages\CreateTurma::route('/create'),
            'edit' => Pages\EditTurma::route('/{record}/edit'),
        ];
    }
}