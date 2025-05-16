<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'departamento',
        'ciudad',
        'celular',
        'email',
        'habeas_data',
    ];
}
