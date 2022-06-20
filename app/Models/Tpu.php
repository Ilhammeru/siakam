<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tpu extends Model
{
    use HasFactory;

    /**
     * Define table name
     * 
     * @return string
     */
    protected $table = 'tpu';

    /**
     * Define fillable field in database
     * 
     * @return array
     */
    protected $fillable = [
        'name',
        'address',
        'phone'
    ];

    public function graves():HasMany
    {
        return $this->hasMany(TpuGrave::class, 'tpu_id', 'id');
    }
}
