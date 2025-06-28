<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;


class Turma extends Model
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use LogsActivity;

    protected $table = 'turmas';

    protected $fillable = [
        'curso_id',
        'periodo_id',
        'turno_id',
        'sigla',
        'ano',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'curso_id',
                'periodo_id',
                'turno_id',
                'sigla',
                'ano',
                'status',
            ]);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }
}