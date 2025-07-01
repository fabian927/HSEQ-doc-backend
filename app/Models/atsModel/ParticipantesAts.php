<?php

namespace App\Models\atsModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantesAts extends Model
{
    use HasFactory;

    protected $table = 'information_ats.participantes_ats';

    protected $fillable = [
        'ats_id',
        'name_part',
        'cedula_part',
        'cargo_part',
        'empresa_part',
        'participacion',
        'firma'
    ];

    public $timestamps = false;
}