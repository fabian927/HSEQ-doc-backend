<?php

namespace App\Models\inspectionHerrManualesModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreguntasHerrManuales extends Model {
    
    protected $table = 'inspection_herramientas.preguntas_ins_herramientas';

    protected $fillable = [
        'inspeccion_id', 
        'numero', 
        'respuesta'
    ];

    public $timestamps = false;

}