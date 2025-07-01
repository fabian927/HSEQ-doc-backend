<?php

namespace App\Models\atsModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoBloqueo extends Model {
    protected $table = 'information_ats.tipo_bloqueo';

    protected $fillable = [
        'ats_id',
        'tipo'
    ];

    public $timestamps = false;
    
}