<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpcsCode extends Model
{
    //
    use HasFactory;
    protected $fillable = [
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
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
