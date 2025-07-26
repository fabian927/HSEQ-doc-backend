<?php

namespace App\Models\PermissionHeightsModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElementsProtect extends Model
{

    protected $table = 'permission_work.elements_protection';

    protected $fillable = [
        'permission_id',
        'nombre',
        'activo'
    ];

    public $timestamps = false;
}