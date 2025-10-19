<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'icon', 'route', 'url', 'parent_id', 'order', 'is_active', 'permissions'
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean'
    ];

    // Relasi untuk submenu
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    // Scope untuk menu aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk menu nonaktif
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    // Scope untuk menu parent (tanpa parent)
    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    // Scope untuk menu dengan parent tertentu
    public function scopeChild($query)
    {
        return $query->whereNotNull('parent_id');
    }

    // Method untuk cek permission
    public function hasPermission($role)
    {
        if (empty($this->permissions)) {
            return true;
        }

        return in_array($role, $this->permissions);
    }

    // Method untuk mendapatkan URL
    public function getMenuUrl()
    {
        if ($this->route) {
            return route($this->route);
        }

        if ($this->url) {
            return url($this->url);
        }

        return '#';
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        return $this->is_active ? 
            '<span class="badge bg-success">Aktif</span>' : 
            '<span class="badge bg-danger">Nonaktif</span>';
    }

    // Method untuk toggle status
    public function toggleStatus()
    {
        $this->update([
            'is_active' => !$this->is_active
        ]);
        
        return $this;
    }
}