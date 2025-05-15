<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpcsPaymentCharge extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'amount',
        'currency',
        'is_active',
        'created_by',
        'updated_by',
    ];

    // public static $rules = [
    //     'amount' => 'required|integer|max:255',
    //     'currency' => 'required|string|max:255'
    // ];

}
