<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persons extends Model
{
    use HasFactory;

    protected $table = 'personas';

    protected $fillable = [
        'name',
        'last_name',
        'doc_type',
        'document',
        'email',
        'phone',
        'address',
        'address',
        'birthdate',
        'age'
    ];
}
