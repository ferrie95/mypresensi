<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuRolePermission extends Model
{
    use HasFactory;

    protected $table = 'menu_role_permissions';
    public $timestamps = false; // Jika tabel tidak punya timestamps
}
