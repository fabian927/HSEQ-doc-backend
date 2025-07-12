<?php

namespace App\Models\inspectionHerrManualesModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantesHerrManuales extends Model
{
    use HasFactory;

    protected $table = 'inspection_herramientas.participantes_ins_herramientas';

    protected $fillable = [
        'inspeccion_id',
        'name_part',
        'cedula_part',
        'cargo_part',
        'empresa_part',
        'firma'
    ];

    public $timestamps = false;
}