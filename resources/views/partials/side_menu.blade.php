<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        @foreach($menus as $menu)
            <li class="nav-item">
                @if (empty($menu['children'])) 
                    <!-- Menu tanpa submenu (root menu) -->
                    <a class="nav-link" href="{{ url($menu['url']) }}">
                        <i class="{{ $menu['icon'] }} menu-icon"></i>
                        <span class="menu-title">{{ $menu['name'] }}</span>
                    </a>
                @else
                    <!-- Menu dengan submenu (menu parent) -->
                    <a class="nav-link" data-toggle="collapse" href="#collapse-{{ $menu['id'] }}" aria-expanded="false" aria-controls="collapse-{{ $menu['id'] }}">
                        <i class="{{ $menu['icon'] }} menu-icon"></i>
                        <span class="menu-title">{{ $menu['name'] }}</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="collapse-{{ $menu['id'] }}">
                        <ul class="nav flex-column sub-menu">
                            @foreach($menu['children'] as $subMenu)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url($subMenu['url']) }}">{{ $subMenu['name'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
</nav>
