<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfessorResource\Pages;
use App\Models\Professor;
use App\Models\Servidor;
use App\Models\Turma;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;

class ProfessorResource extends Resource
{
    protected static ?string $model = Professor::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = "Gerenciamento Escolar";

    protected static ?int $navigationSort = 1;

    public static ?string $modelLabel = 'Professor';

    public static ?string $pluralModelLabel = 'Professores';

    public static ?string $slug = 'professores';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('servidor_id')
                    ->label('Servidor')
                    ->options(function () {
                        return Servidor::with(['cargo.regimeContratual'])
                            ->whereHas('cargo', fn($q) => $q->where('nome', 'Professor'))
                            ->get()
                            ->mapWithKeys(function ($servidor) {
                                $cargo = $servidor->cargo?->nome ?? '-';
                                $regime = $servidor->cargo?->regimeContratual?->nome ?? '-';
                                $label = "{$servidor->matricula} - {$servidor->nome} ({$cargo} - {$regime})";
                                return [$servidor->id => $label];
                            });
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn(Get $get, Set $set) => $set('setor_turma_id', null)),

                Forms\Components\Select::make('setor_turma_id')
                    ->label('Turma')
                    ->options(function (Get $get) {
                        $servidorId = $get('servidor_id');

                        if (!$servidorId) {
                            return [];
                        }

                        // Busca os setores do servidor
                        $servidor = Servidor::with('setores')->find($servidorId);
                        $setorIds = $servidor?->setores->pluck('id') ?? [];

                        if ($setorIds->isEmpty()) {
                            return [];
                        }

                        // Retorna as turmas cujos setor_id estão entre os setores do servidor
                        return Turma::whereIn('setor_id', $setorIds)
                            ->with(['nomeTurma', 'siglaTurma'])
                            ->get()
                            ->mapWithKeys(fn($turma) => [$turma->id => $turma->nomeCompleto()]);
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive(),

                Forms\Components\Select::make('aula_id')
                    ->label('Aula')
                    ->relationship('aula', 'nome')
                    ->preload()
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('servidor.nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('setorTurma.turma.nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('aula.nome')
                    ->searchable()
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
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProfessors::route('/'),
        ];
    }
}
