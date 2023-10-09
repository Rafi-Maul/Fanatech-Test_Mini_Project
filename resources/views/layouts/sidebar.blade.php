<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="index.html">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        {{-- Pages --}}

        <li class="nav-heading">Pages</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="users-profile.html">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li><!-- End Profile Page Nav -->

        @if (Auth::user()->role == 'SuperAdmin')
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('inventory.index') }}">
                    <i class="bi bi-envelope"></i>
                    <span>Inventory</span>
                </a>
            </li>
        @endif

        @if (Auth::user()->role != 'Purchase')
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('sales.index') }}">
                    <i class="bi bi-envelope"></i>
                    <span>Sales</span>
                </a>
            </li>
        @endif

        @if (Auth::user()->role != 'Sales')
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('purchases.index') }}">
                    <i class="bi bi-envelope"></i>
                    <span>Purchase</span>
                </a>
            </li>
        @endif

        <li class="nav-item">
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf

                <button class="nav-link collapsed w-100 border-0" type="submit">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </li>
        <!-- End Login Page Nav -->

    </ul>

</aside>
