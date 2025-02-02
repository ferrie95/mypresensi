<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    // Kolom yang bisa diisi
    protected $fillable = ['nik', 'name', 'password'];

    // Sembunyikan kolom tertentu
    protected $hidden = ['password'];

    /**
     * Relasi ke tabel roles melalui tabel pivot user_roles.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'employee_id', 'role');
    }
}
