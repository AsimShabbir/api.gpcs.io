<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpcsCodesHistory extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'gpcs_codes_id',
        'user_id',
        'country_code',
        'first_part',
        'second_part',
        'gpcscode',
        'domain',
        'latitude',
        'longitude',
        'label',
        'is_deleted',
        'verified',
        'paid',
        'amount',
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
