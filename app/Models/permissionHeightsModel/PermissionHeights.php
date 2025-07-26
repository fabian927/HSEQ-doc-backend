<?php

namespace App\Models\PermissionHeightsModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PermissionHeightsModel\RespPermission;

class PermissionHeights extends Model
{
    use HasFactory;

    protected $table = 'permission_work.permission_heights';

    protected $fillable = [
        'usuario_id',
        'responsable',
        'proyecto',
        'ejecutor',
        'altura_trabajo',
        'altura_caida',
        'lugar_exacto',
        'equipo',
        'fecha_inicio',
        'fecha_fin',
        'herramientas',
        'departamento',
        'municipio',
        'actividad',
        'n_personas',
        'observaciones',
        'otro_elemento',
        'otro_elemento_protect'
    ];

    public function accessType () {
        return $this->hasMany(AccessType::class, 'permission_id');
    }

    public function elementsProtect () {
        return $this->hasMany(ElementsProtect::class, 'permission_id');
    }

    public function askPermission () {
        return $this->hasMany(AskPermission::class, 'permission_id');
    }

    public function partPermission(){
        return $this->hasMany(PartPermission::class, 'permission_id');
    }

    public function respPermission(){
        return $this->hasMany(RespPermission::class, 'permission_id');
    }
}