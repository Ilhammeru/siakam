<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'birth_date',
        'regency_of_birth',
        'address',
        'village_id',
        'rt',
        'rw',
        'reporters_name',
        'reporters_nik',
        'place_of_death',
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
}
