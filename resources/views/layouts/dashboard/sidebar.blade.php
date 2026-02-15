
<aside class="sidenav navbar navbar-vertical navbar-expand-xs fixed-start ms-2 my-3 
             border-0 border-radius-xl shadow-lg bg-white">

    <div class="sidenav-header text-center py-3">
        <a class="navbar-brand m-0 d-flex align-items-center justify-content-center gap-2"
           href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('assets/img/logo-ct-dark.png') }}" width="28">
            <span class="fw-bold text-dark">System Panel</span>
        </a>
    </div>

    <hr class="horizontal dark mt-0 mb-3">

    <div class="collapse navbar-collapse w-auto">
        <ul class="navbar-nav px-2">

            {{-- ================= ADMIN ================= --}}
            @role('admin')

            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                   href="{{ route('admin.dashboard') }}">
                    <i class="material-symbols-rounded">dashboard</i>
                    <span class="nav-link-text ms-2">Dashboard</span>
                </a>
            </li>

            @can('users.view')
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                   href="{{ route('admin.users.index') }}">
                    <i class="material-symbols-rounded">group</i>
                    <span class="nav-link-text ms-2">Users</span>
                </a>
            </li>
            @endcan

            @can('teams.view')
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('admin.teams.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                   href="{{ route('admin.teams.index') }}">
                    <i class="material-symbols-rounded">diversity_3</i>
                    <span class="nav-link-text ms-2">Teams</span>
                </a>
            </li>
            @endcan

            @can('projects.view')
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                   href="{{ route('admin.projects.index') }}">
                    <i class="material-symbols-rounded">folder</i>
                    <span class="nav-link-text ms-2">Projects</span>
                </a>
            </li>
            @endcan
                
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('admin.systems.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                   href="{{ route('admin.systems.index') }}">
                    <i class="material-symbols-rounded">folder</i>
                    <span class="nav-link-text ms-2">Stsyem</span>
                </a>
            </li>


            @can('tasks.view')
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('admin.tasks.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                   href="{{ route('admin.tasks.index') }}">
                    <i class="material-symbols-rounded">task</i>
                    <span class="nav-link-text ms-2">Tasks</span>
                </a>
            </li>
            @endcan

            @can('roles.view')
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                   href="{{ route('admin.roles.index') }}">
                    <i class="material-symbols-rounded">person</i>
                    <span class="nav-link-text ms-2">Roles</span>
                </a>
            </li>
            @endcan

            @endrole


            {{-- ================= LEADER ================= --}}
            @role('leader')
            <li class="nav-item mt-3">
                <h6 class="ps-4 text-uppercase text-xs text-muted">Leader</h6>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('leader.dashboard') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                   href="{{ route('leader.dashboard') }}">
                    <i class="material-symbols-rounded">dashboard</i>
                    Dashboard
                </a>
            </li>

            @can('tasks.assign')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('leader.taskboard') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                   href="{{ route('leader.taskboard') }}">
                    <i class="material-symbols-rounded">task</i>
                    Team Tasks
                </a>
            </li>
            @endcan

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('leader.projects.index') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                   href="{{ route('leader.projects.index') }}">
                    <i class="material-symbols-rounded">folder</i>
                    Team Projects
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('leader.engineers.performance') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                   href="{{ route('leader.engineers.performance') }}">
                    <i class="material-symbols-rounded">person</i>
                    Engineers performance
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('notifications.index') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                   href="{{ route('notifications.index') }}">
                    <i class="material-symbols-rounded">notifications</i>
                    My Notifications
                </a>
            </li>
            @endrole


            {{-- ================= ENGINEER ================= --}}
            @role('engineer')
            <li class="nav-item mt-3">
                <h6 class="ps-4 text-uppercase text-xs text-muted">Engineer</h6>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('engineer.dashboard') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                   href="{{ route('engineer.dashboard') }}">
                    <i class="material-symbols-rounded">dashboard</i>
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('engineer.tasks') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                   href="{{ route('engineer.tasks') }}">
                    <i class="material-symbols-rounded">task</i>
                    <span class="nav-link-text ms-2">My Taks</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('engineer.projects') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                   href="{{ route('engineer.projects') }}">
                    <i class="material-symbols-rounded">folder</i>
                    My Projects
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('notifications.index') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                   href="{{ route('notifications.index') }}">
                    <i class="material-symbols-rounded">notifications</i>
                    My Notifications
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('engineer.profile') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                   href="{{ route('engineer.profile') }}">
                    <i class="material-symbols-rounded">person</i>
                    My Profile
                </a>
            </li>
            @endrole

        </ul>
    </div>
    <div class="sidenav-footer position-absolute bottom-0 w-100 px-3 pb-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn bg-gradient-dark w-100 border-radius-lg shadow">
                LOGOUT
            </button>
        </form>
    </div>
</aside>
