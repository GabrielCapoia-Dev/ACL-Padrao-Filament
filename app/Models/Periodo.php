<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Periodo extends Model
{
    use HasFactory;
    use Notifiable;
    use HasRoles;

    protected $table = 'periodos';
    
    protected $fillable = [
        'nome',
    ];
}