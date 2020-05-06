<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ config('app.default_avatar') }}" alt="{{ config('app.name') }}" class="brand-image img-circle elevation-1"
             style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                @isset($abilities)
                    @foreach ($abilities['view'] as $ability)
                        @if(!$ability->use_url && (($ability->show_on_menu && count($ability->children)) || !$ability->alias))
                            {{--子菜单--}}
                            <li class="nav-item has-treeview {{ checkMenuOpen($ability->children) }}">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas {{ $ability->icon }}"></i>
                                    <p>
                                        {{ $ability->name }}
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                @foreach ($ability->children as $childAbility)
                                    @if(($childAbility->show_on_menu && count($childAbility->children)) || !$childAbility->alias)
                                        {{--子菜单--}}
                                        <li class="nav-item has-treeview {{ checkMenuOpen($childAbility->children) }}">
                                            <a href="#" class="nav-link">
                                                <i class="nav-icon fas {{ $childAbility->icon }}"></i>
                                                <p>
                                                    {{ $childAbility->name }}
                                                    <i class="fas fa-angle-left right"></i>
                                                </p>
                                            </a>
                                            <ul class="nav nav-treeview">
                                                @foreach ($childAbility->children as $lastChildAbility)
                                                    @if($lastChildAbility->show_on_menu)
                                                    <li class="nav-item">
                                                        <a href="{{ route($lastChildAbility->alias) }}" class="nav-link  {{ checkMenuActive($lastChildAbility) }}">
                                                            <i class="fas {{ $lastChildAbility->icon }} nav-icon"></i>
                                                            <p>{{ $lastChildAbility->name }}</p>
                                                        </a>
                                                    </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    @else
                                        @if($childAbility->show_on_menu)
                                        <li class="nav-item">
                                            <a href="{{ route($childAbility->alias) }}" class="nav-link {{ checkMenuActive($childAbility) }}">
                                                <i class="nav-icon fas {{ $childAbility->icon }}"></i>
                                                <p>
                                                    {{ $childAbility->name }}
                                                </p>
                                            </a>
                                        </li>
                                        @endif
                                    @endif
                                @endforeach
                                </ul>
                            </li>
                        @else
                            {{--无子菜单--}}
                            @if($ability->show_on_menu)
                            <li class="nav-item">
                                <a href="@if($ability->alias){{ route($ability->alias) }}@else {{ $ability->url }}@endif" class="nav-link {{ checkMenuActive($ability) }}">
                                    <i class="fas {{ $ability->icon }} nav-icon"></i>
                                    <p>{{ $ability->name }}</p>
                                </a>
                            </li>
                            @endif
                        @endif
                    @endforeach
                @endisset
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
