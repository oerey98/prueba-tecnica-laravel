<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concurso extends Model
{
    use HasFactory;

    protected $table = 'concursos';

    protected $fillable = [
        'id_cliente',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
}
