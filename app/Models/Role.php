<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_deleted',
        'created_by',
        'updated_by',
    ];

    public static $rules = [
        'name' => 'required|string|max:255'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

}
