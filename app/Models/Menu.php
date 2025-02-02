<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    public function subMenus()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'menu_role_permissions', 'menu_id', 'role');
    }
}
