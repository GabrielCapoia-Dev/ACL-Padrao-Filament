<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServidorResource\Pages;
use App\Filament\Resources\ServidorResource\RelationManagers;
use App\Models\Servidor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServidorResource extends Resource
{
    protected static ?string $model = Servidor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static ?string $modelLabel = 'Servidor';

    protected static ?string $navigationGroup = "Gerenciamento";

    public static ?string $pluralModelLabel = 'Servidores';

    public static ?string $slug = 'servidores';

    public static function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with('setores');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('matricula')
                    ->unique(ignoreRecord: true)
                    ->label('Matricula')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('nome')
                    ->label('Nome')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->unique(ignoreRecord: true)
                    ->email()
                    ->required(),
                Forms\Components\Select::make('cargo_id')
                    ->label('Cargo')
                    ->relationship('cargo', 'nome')
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->nome} - {$record->regimeContratual->nome}")
                    ->required(),
                Forms\Components\Select::make('turno_id')
                    ->label('Turno')
                    ->relationship('turno', 'nome')
                    ->required(),
                Forms\Components\Select::make('setores')
                    ->label('Setores')
                    ->relationship('setores', 'nome')
                    ->preload()
                    ->multiple()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50, 100])
            ->columns([
                Tables\Columns\TextColumn::make('setores')
                    ->label('Setores')
                    ->getStateUsing(
                        fn($record) =>
                        $record->setores && $record->setores->count() > 0
                            ? $record->setores->pluck('nome')->join(', ')
                            : null
                    )
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('matricula')
                    ->sortable()
                    ->label('Matricula')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nome')
                    ->sortable()
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cargo.nome')
                    ->sortable()
                    ->label('Cargo')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('cargo.regimeContratual.nome')
                    ->sortable()
                    ->label('Regime Contratual')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('turno.nome')
                    ->sortable()
                    ->label('Turno')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
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
                
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListServidors::route('/'),
            'create' => Pages\CreateServidor::route('/create'),
            'edit' => Pages\EditServidor::route('/{record}/edit'),
        ];
    }
}
