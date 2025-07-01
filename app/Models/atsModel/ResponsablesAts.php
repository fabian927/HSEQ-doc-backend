<?php

namespace App\Models\atsModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsablesAts extends Model
{
    use HasFactory;

    protected $table = 'information_ats.responsables_ats';

    protected $fillable = [
        'ats_id',
        'name_resp',
        'tipo_responsable',
        'firma'
    ];

    public $timestamps = false;
}