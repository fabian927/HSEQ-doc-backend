<?php

namespace App\Models\atsModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreguntaAts extends Model {
    
    protected $table = 'information_ats.preguntas_ats';

    protected $fillable = [
        'ats_id', 
        'numero', 
        'respuesta'
    ];

    public $timestamps = false;

}