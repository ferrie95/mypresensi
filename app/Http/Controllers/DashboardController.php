<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            $employee = Auth::user();

            if ($employee && $employee->roles->isNotEmpty()) {
                $menus = $employee->roles
                    ->flatMap(fn($role) => $role->menus)
                    ->unique('id')
                    ->sortBy('order'); // Urutkan menu berdasarkan kolom `order`

                // Konversi menu ke bentuk hierarki
                $menus = $this->buildMenuHierarchy($menus);
            } else {
                $menus = [];
            }

            return view('dashboard', compact('menus'));
        } catch (\Exception $e) {
            Log::error('Error pada DashboardController: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat memuat dashboard.');
        }
    }

    /**
     * Mengatur menu menjadi hierarki berdasarkan parent_id.
     *
     * @param \Illuminate\Support\Collection $menus
     * @return array
     */
    private function buildMenuHierarchy($menus)
    {
        $menuHierarchy = [];
        $menus = $menus->groupBy('parent_id');

        foreach ($menus->get(null, []) as $menu) {
            $menuHierarchy[] = $this->buildMenuNode($menu, $menus);
        }

        return $menuHierarchy;
    }

    /**
     * Membuat node menu dengan anak-anaknya.
     *
     * @param \App\Models\Menu $menu
     * @param \Illuminate\Support\Collection $menus
     * @return array
     */
    private function buildMenuNode($menu, $menus)
    {
        $menuNode = [
            'id' => $menu->id,
            'name' => $menu->name,
            'url' => $menu->url,
            'icon' => $menu->icon,
            'children' => [],
        ];

        if ($menus->has($menu->id)) {
            foreach ($menus->get($menu->id) as $childMenu) {
                $menuNode['children'][] = $this->buildMenuNode($childMenu, $menus);
            }
        }

        return $menuNode;
    }

}

