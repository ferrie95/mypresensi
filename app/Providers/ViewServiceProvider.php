<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('partials.side_menu', function ($view) {
            try {
                // Jika user belum login, jangan tampilkan menu
                if (!Auth::check()) {
                    $view->with('menus', []);
                    return;
                }

                $employee = Auth::user();

                // Pastikan employee memiliki roles dan menus
                if ($employee && $employee->roles->isNotEmpty()) {
                    $menus = $employee->roles
                        ->flatMap(fn($role) => $role->menus)
                        ->unique('id')
                        ->sortBy('order'); // Urutkan menu berdasarkan kolom `order`
                    
                    // Konversi menu ke bentuk hierarki
                    $menus = self::buildMenuHierarchy($menus);
                } else {
                    $menus = [];
                }

                // Kirim data menu ke view side_menu
                $view->with('menus', $menus);

            } catch (\Exception $e) {
                Log::error('Error pada ViewServiceProvider: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                ]);

                // Jika error, kirim menu kosong agar tidak menyebabkan error di Blade
                $view->with('menus', []);
            }
        });
    }

    /**
     * Mengatur menu menjadi hierarki berdasarkan parent_id.
     *
     * @param \Illuminate\Support\Collection $menus
     * @return array
     */
    private static function buildMenuHierarchy($menus)
    {
        $menuHierarchy = [];
        $menus = $menus->groupBy('parent_id');

        foreach ($menus->get(null, []) as $menu) {
            $menuHierarchy[] = self::buildMenuNode($menu, $menus);
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
    private static function buildMenuNode($menu, $menus)
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
                $menuNode['children'][] = self::buildMenuNode($childMenu, $menus);
            }
        }

        return $menuNode;
    }
}
