<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Curso extends Model
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    
    protected $table = 'cursos';

    protected $fillable = [
        'nome',
    ];
    
    public function turmas()
    {
        return $this->hasMany(Turma::class);
    }
}