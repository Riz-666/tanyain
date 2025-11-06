<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'username',
        'email',
        'bio',
        'password',
        'foto',
        'role',
        'instagram',
        'linkedin',
        'github',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function artikel()
    {
        return $this->hasMany(Artikel::class)->latest();
    }

    public function repositori()
    {
        return $this->hasMany(Repositori::class)->latest();
    }

    public function viewArtikel()
    {
        return $this->hasMany(viewArtikel::class);
    }

    public function saran()
    {
        return $this->hasMany(Saran::class);
    }

    public function getNameAttribute()
    {
        return $this->nama;
    }

    public function setNameAttribute($value)
    {
        $this->nama = $value;
    }
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'user_id');
    }
}
