<?php

namespace App\Models\PermissionHeightsModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessType extends Model
{
    protected $table = 'permission_work.access_type';

    protected $fillable = [
        'permission_id',
        'nombre',
        'activo'
    ];

    public $timestamps = false;

}