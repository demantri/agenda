<ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
        <a class="nav-link {{ Request::is('agenda') ? '' : 'collapsed' }}" href="{{ url('agenda') }}">
          <i class="bi bi-calendar"></i>
          <span>Agenda</span>
        </a>
    </li>

    @if (Auth::check() == true && Auth::user()->role_name == 'superadmin')
    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#" aria-expanded="true">
          <i class="bi bi-menu-button-wide"></i><span>Master Data</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav" style="">
            <li>
                <a href="{{ url('user') }}">
                    <i class="bi bi-circle"></i><span>User</span>
                </a>
            </li>
            <li>
                <a href="{{ url('unit') }}">
                    <i class="bi bi-circle"></i><span>Unit/bagian</span>
                </a>
            </li>
        </ul>
    </li>
    @endif
</ul>