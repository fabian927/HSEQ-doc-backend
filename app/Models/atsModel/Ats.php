<?php

namespace App\Models\atsModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\atsModel\ParticipantesAts;
use App\Models\atsModel\ResponsablesAts;


class Ats extends Model
{
    use HasFactory;

    protected $table = 'information_ats.ats';

    protected $fillable = [
        'usuario_id',
        'proyecto',
        'fecha',
        'actividad',
        'responsable',
        'ejecutor',
        'requiere_bloqueo',
        'detalle_bloqueo',
        'otro_elemento'
    ];

    public function tiposBloqueo() {
    return $this->hasMany(TipoBloqueo::class, 'ats_id');
    }

    public function elementosProteccion() {
        return $this->hasMany(ElementosProteccion::class, 'ats_id');
    }

    public function preguntas() {
        return $this->hasMany(PreguntaAts::class, 'ats_id');
    }

    public function participantesAts(){
        return $this->hasMany(ParticipantesAts::class, 'ats_id');
    }

    public function responsablesAts(){
        return $this->hasMany(ResponsablesAts::class, 'ats_id');
    }
}