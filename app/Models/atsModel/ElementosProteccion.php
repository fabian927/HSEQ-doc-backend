<?php

namespace App\Models\atsModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElementosProteccion extends Model {
    
    protected $table = 'information_ats.elementos_proteccion';

    protected $fillable = [
        'ats_id',
        'nombre',
        'activo'
    ];

    public $timestamps = false;
}
