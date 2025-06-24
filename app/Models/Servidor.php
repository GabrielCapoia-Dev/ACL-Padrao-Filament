<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Servidor extends Model
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use LogsActivity;


    protected $table = 'servidores';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'matricula',
        'nome',
        'email',
        'cargo_id',
        'turno_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'matricula',
                'nome',
                'email',
                'cargo_id',
                'turno_id',
            ]);
    }


    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }

    public function setores()
    {
        return $this->belongsToMany(Setor::class, 'servidor_setor');
    }

    public function cargaHoraria()
    {
        return $this->hasOne(CargaHoraria::class);
    }
}
