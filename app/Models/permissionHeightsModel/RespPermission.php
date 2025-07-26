<?php

namespace App\Models\PermissionHeightsModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespPermission extends Model
{
    use HasFactory;

    protected $table = 'permission_work.resp_permission';

    protected $fillable = [
        'permission_id',
        'nombre_resp',
        'cedula_resp',
        'cargo_resp',
        'firma'
    ];

    public $timestamps = false;

}