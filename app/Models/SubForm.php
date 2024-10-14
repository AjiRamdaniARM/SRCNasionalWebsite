<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubForm extends Model
{
    use HasFactory;
    protected $fillable = [
        'link_form',
    ];
}
