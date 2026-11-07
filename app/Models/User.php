<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    /**
     * Sesuai ERD: nama, username, password, role
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'username',
        'password',
        'password_hint',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'password_hint',
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
            'password' => 'hashed',
        ];
    }

    // Relasi sesuai ERD: USERS memiliki/mengelola SISWA & mencatat PEMBAYARAN
    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id_user', 'id_user');
    }

    public function getNameAttribute()
    {
        return $this->nama;
    }

    public function getPasswordPlainAttribute()
    {
        if (empty($this->password_hint)) {
            return '';
        }

        try {
            return \Illuminate\Support\Facades\Crypt::decryptString($this->password_hint);
        } catch (\Throwable $e) {
            return '';
        }
    }

    public function pembayaranDicatat()
    {
        return $this->hasMany(Pembayaran::class, 'id_user', 'id_user');
    }
}
