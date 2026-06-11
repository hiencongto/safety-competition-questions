<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnserDatabase extends Model
{
    protected $table = 'anser_database';

    protected $fillable = [
        'question',
        'answer',
        'is_answered',
    ];
}