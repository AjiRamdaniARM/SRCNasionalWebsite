<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableSesi extends Model
{
    use HasFactory;
    protected $fillable = [
        'sesi',
        'waktu_awal',
        'waktu_akhir',
        'duration',
    ];
}
