<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\Roles;
use Carbon\Carbon;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'otp_code',
        'first_name',
        'last_name',
        'contact_number',
        'password',
        'verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Roles::class,
        ];
    }
    public function gpcscodes()
    {
        return $this->hasMany('App\Models\GpcsCode');
    }
    public function gpcscodeshistory()
    {
        return $this->hasMany('App\Models\GpcsCodesHistory');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }
    //define scopes
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }


}
