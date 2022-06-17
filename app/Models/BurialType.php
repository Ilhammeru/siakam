<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BurialType extends Model
{
    use HasFactory;

    /**
     * Define table name
     * 
     * @return string
     */
    protected $table = 'burial_type';

    /**
     * Define fillable field in database
     * 
     * @return array
     */
    protected $fillable = ['name'];
}
