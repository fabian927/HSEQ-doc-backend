<?php

namespace App\Models\PermissionHeightsModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartPermission extends Model
{
    use HasFactory;

    protected $table = 'permission_work.part_permission';

    protected $fillable = [
        'permission_id',
        'nombre_part',
        'cedula_part',
        'cargo_part',
        'empresa_part',
        'firma'
    ];

    public $timestamps = false;

}