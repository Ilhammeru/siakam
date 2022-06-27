<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BurialData extends Model
{
    use HasFactory;

    /**
     * Define table name
     * 
     * @return string
     */
    protected $table = 'burial_data';

    /**
     * Define fillable field in database
     * 
     * @return array
     */
    protected $fillable = [
        'name',
        'nik',
        'gender',
        'religion',
        'birth_date',
        'regency_of_birth',
        'address',
        'village_id',
        'rt',
        'rw',
        'reporters_name',
        'reporters_nik',
        'reporters_phone',
        'reporters_relationship',
        'reporters_address',
        'date_of_death',
        'regency_of_death',
        'buried_date',
        'burial_type_id',
        'grave_block',
        'grave_number',
        'notes',
        'longitude',
        'latitude',
        'grave_photo',
        'application_letter_photo',
        'ktp_corpse_photo',
        'kk_corpse_photo',
        'cover_letter_photo',
        'reporter_ktp_photo',
        'reporter_kk_photo',
        'letter_of_hospital_statement_photo',
        'guardian_name',
        'guardian_phone',
        'tpu_id'
    ];

    public function graveBlock():BelongsTo
    {
        return $this->belongsTo(TpuGrave::class, 'grave_block', 'id');
    }

    public function tpu():BelongsTo
    {
        return $this->belongsTo(Tpu::class, 'tpu_id', 'id');
    }

    public function burialType():BelongsTo
    {
        return $this->belongsTo(BurialType::class, 'burial_type_id', 'id');
    }
}
