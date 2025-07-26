<?php

namespace App\Models\PermissionHeightsModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AskPermission extends Model
{

    protected $table = 'permission_work.ask_permission';

    protected $fillable = [
        'permission_id',
        'numero',
        'respuesta'
    ];

    public $timestamps = false;

}