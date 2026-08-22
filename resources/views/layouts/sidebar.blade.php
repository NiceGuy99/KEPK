<nav id="sidebar">    
    <ul class="nav flex-column nav-sidebar mt-4">
        @if(isset($sidebarMenus) && $sidebarMenus->isNotEmpty())
            @foreach($sidebarMenus as $menu)
                @php
                    $isActive = false;
                    $patterns = explode('|', $menu->route_pattern ?? $menu->route_name);
                    foreach ($patterns as $p) {
                        $p = trim($p);
                        if (!empty($p) && request()->routeIs($p)) {
                            $isActive = true;
                            break;
                        }
                    }
                    $menuUrl = Route::has($menu->route_name) ? route($menu->route_name) : url($menu->url ?? '#');
                @endphp
                <li class="nav-item">
                    <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ $menuUrl }}">
                        <i class="{{ $menu->icon }}"></i> {{ $menu->nama_menu }}
                    </a>
                </li>
            @endforeach
        @endif
    </ul>
</nav>
