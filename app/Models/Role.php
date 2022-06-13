<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that define table name
     * 
     * @var string
     */
    protected $table = 'roles';

    /**
     * The atrribute that define fillable field
     * 
     * @var array
     */
    protected $fillable = ['name'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'role', 'id');
    }
}
