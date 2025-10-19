<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        // Gunakan query builder dengan scope parent()
        $menus = Menu::with(['children' => function($query) {
                $query->orderBy('order');
            }])
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
            
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        // Gunakan whereNull untuk mendapatkan parent menus
        $parentMenus = Menu::whereNull('parent_id')->get();
        return view('admin.menus.create', compact('parentMenus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'required|integer',
            'permissions' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        Menu::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'route' => $request->route,
            'url' => $request->url,
            'parent_id' => $request->parent_id,
            'order' => $request->order,
            'permissions' => $request->permissions,
            'is_active' => $request->has('is_active') ? true : false
        ]);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil dibuat.');
    }

    public function edit(Menu $menu)
    {
        // Dapatkan parent menus kecuali menu yang sedang diedit
        $parentMenus = Menu::whereNull('parent_id')
            ->where('id', '!=', $menu->id)
            ->get();
            
        return view('admin.menus.edit', compact('menu', 'parentMenus'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'required|integer',
            'permissions' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $menu->update([
            'name' => $request->name,
            'icon' => $request->icon,
            'route' => $request->route,
            'url' => $request->url,
            'parent_id' => $request->parent_id,
            'order' => $request->order,
            'permissions' => $request->permissions,
            'is_active' => $request->has('is_active') ? true : false
        ]);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        // Hapus children terlebih dahulu jika ada
        $menu->children()->delete();
        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif menu
     */
    public function toggleStatus(Menu $menu)
    {
        $menu->toggleStatus();
        
        $status = $menu->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('admin.menus.index')
            ->with('success', "Menu {$menu->name} berhasil $status.");
    }

    /**
     * Aktifkan semua menu (batch operation)
     */
    public function activateAll()
    {
        Menu::query()->update(['is_active' => true]);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Semua menu berhasil diaktifkan.');
    }

    /**
     * Nonaktifkan semua menu (batch operation)
     */
    public function deactivateAll()
    {
        Menu::query()->update(['is_active' => false]);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Semua menu berhasil dinonaktifkan.');
    }
}