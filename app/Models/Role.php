<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    // Relasi ke Employee
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'user_role', 'role', 'employee_id');
    }

    // Relasi ke Menu
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_role_permissions', 'role', 'menu_id');
    }
}
