<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notices extends Model
{
    use HasFactory;

    protected $table = 'notices';

    protected $fillable = [
        'title',
        'date',
        'content',
    ];
}
