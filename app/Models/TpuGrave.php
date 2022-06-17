<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TpuGrave extends Model
{
    use HasFactory;

    /**
     * Define table name
     * 
     * @return string
     */
    protected $table = 'tpu_grave';

    /**
     * Define fillable field in database
     * 
     * @return array
     */
    protected $fillable = [
        'tpu_id',
        'grave_block',
        'quota'
    ];
}
