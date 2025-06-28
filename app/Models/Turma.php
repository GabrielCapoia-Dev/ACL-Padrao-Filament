<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;


class Turma extends Model
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    // use LogsActivity;

    protected $table = 'turmas';
    
    protected $fillable = [
        'turma',
    ];
}