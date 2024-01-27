<style>
    /* .navbar-nav > .sidebar-brand {
        height:9rem;
    } */
    .toggled > .sidebar-brand {
        height:3rem;
    }
</style>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <!-- <img src="/images/Logo.png" alt="takvpn" style="width:100%;" /> -->
        <div class="sidebar-brand-text mx-3">
            Logo
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item" id="dashboard">
        <a class="nav-link" href="{{route('admin.dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <li class="nav-item" id="categories">
        <a class="nav-link" href="{{route('admin.categories.index')}}">
            <i class="fas fa-fw fa-th"></i>
            <span>Categories</span>
        </a>
    </li>

    <li class="nav-item" id="products">
        <a class="nav-link" href="{{route('admin.products.index')}}">
            <i class="fas fa-fw fa-box-open"></i>
            <span>Products</span>
        </a>
    </li>


    <li class="nav-item" id="services">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-server"></i>
            <span>Services</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <li class="nav-item" id="messages">
        <a class="nav-link" href="{{route('admin.notifications')}}">
            <i class="fas fa-fw fa-envelope"></i>
            <span>Messages</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <li class="nav-item" id="transactions">
        <a class="nav-link" href="{{route('admin.transactions', 'all')}}">
            <i class="fas fa-fw fa-euro-sign"></i>
            <span>Transactions</span>
        </a>
    </li>

    <li class="nav-item" id="orders">
        <a class="nav-link" href="{{route('admin.orders', 'all')}}">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Orders</span>
        </a>
    </li>

    <li class="nav-item" id="discounts">
        <a class="nav-link" href="{{route('admin.discounts.index')}}">
            <i class="fas fa-fw fa-percent"></i>
            <span>Discounts</span>
        </a>
    </li>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<li class="nav-item" id="users">
    <a class="nav-link" href="{{route('admin.users.index')}}">
        <i class="fas fa-fw fa-users"></i>
        <span>Users</span>
    </a>
</li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>

<script>
    window.onload = function() {
        var active = "{{$active}}";
        document.getElementById(active).classList.add("active");
    }
</script>
<!-- End of Sidebar -->
