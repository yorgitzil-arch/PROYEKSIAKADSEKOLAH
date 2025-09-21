<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'parent_id',
        'order',
        'is_active',
    ];

    /**
     * Get the parent menu for the menu item.
     */
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Get the child menus for the menu item.
     */
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    /**
     * Scope a query to only include root menus (menus without a parent).
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}
