<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'id'; 
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'fecha_cita',
        'hora_cita',
        'nombre_medico',
        'nombre_centro',
        'telefono',
    ];
}
