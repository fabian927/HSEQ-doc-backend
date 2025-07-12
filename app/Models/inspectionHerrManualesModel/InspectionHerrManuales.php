<?php

namespace App\Models\inspectionHerrManualesModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\inspectionHerrManualesModel\PreguntasHerrManuales;
use App\Models\inspectionHerrManualesModel\ParticipantesHerrManuales;

class InspectionHerrManuales extends Model
{
    use HasFactory;

    protected $table = 'inspection_herramientas.herramientas_manuales';

    protected $fillable = [
        'usuario_id',
        'responsable',
        'proyecto',
        'ejecutor',
        'fecha',
        'departamento',
        'municipio',
        'actividad'
    ];

    public function preguntasInspection() {
        return $this->hasMany(PreguntasHerrManuales::class, 'inspeccion_id');
    }

    public function participantesInspection(){
        return $this->hasMany(ParticipantesHerrManuales::class, 'inspeccion_id');
    }
}