<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TldCountryCode extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'domain_country_code',
        'map_code',
        'country_name',
    ];
}
