<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'no_hp',
        'foto',
        'role',
        'anggota_keluarga_id',
        'password',
    ];

    public function getInitialAttribute(): string
    {
        return strtoupper(substr($this->name ?? 'A', 0, 1));
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin'    => 'Administrator',
            'ketua'    => 'Ketua RT',
            'pengurus' => 'Pengurus RT',
            default    => 'Warga',
        };
    }

    public function getRoleBadgeAttribute(): string
    {
        return match ($this->role) {
            'admin'    => 'bg-blue-50 text-blue-700',
            'ketua'    => 'bg-green-50 text-green-700',
            'pengurus' => 'bg-amber-50 text-amber-600',
            default    => 'bg-slate-100 text-slate-600',
        };
    }

    /**
     * Administrator & Ketua RT boleh mengelola data kependudukan (CRUD).
     */
    public function canManageKependudukan(): bool
    {
        return in_array($this->role, ['admin', 'ketua'], true);
    }

    /**
     * Hanya Administrator & Ketua RT yang boleh mengelola akun pengguna.
     */
    public function canManageAkun(): bool
    {
        return in_array($this->role, ['admin', 'ketua'], true);
    }

    public function getFotoUrlAttribute(): ?string
    {
        if ($this->foto && file_exists(public_path($this->foto))) {
            return asset($this->foto);
        }
        return null;
    }

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
        ];
    }

    /** Data kependudukan yang ditautkan ke akun ini, bila ada. */
    public function anggotaKeluarga(): BelongsTo
    {
        return $this->belongsTo(AnggotaKeluarga::class);
    }
}
