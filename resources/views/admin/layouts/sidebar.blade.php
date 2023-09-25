<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  {{-- <a href="index3.html" class="brand-link">
    <img src="{{ asset('assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">{{env('APP_NAME')}}</span>
  </a> --}}

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
     <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div> 
    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        {{-- <li class="nav-item">
          <a href="" class="nav-link ">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li> --}}

        <li class="nav-item  {{ request()->routeIs('question.*') ? ' menu-is-opening menu-open' : '' }}">
          <a href="{{route('question.index')}}" class="nav-link ">
            <i class="nav-icon fa fa-question-circle"></i>
            <p>
              Questions
            </p> 
          </a>
        </li>
        <li class="nav-item  {{ request()->routeIs('subquestion.*') ? ' menu-is-opening menu-open' : '' }}">
          <a href="{{route('subquestion.index')}}" class="nav-link ">
            <i class="nav-icon fa fa-question-circle"></i>
            <p>
              Sub Questions
            </p> 
          </a>
        </li>
         {{-- <li class="nav-item 
          {{ request()->routeIs('tag.*') || request()->routeIs('category.*') ||  request()->routeIs('plan.*')  ? ' menu-is-opening menu-open' : '' }}
          ">
          <a href="#" class="nav-link 
            nav-link   {{ request()->routeIs('tag.*') ||  request()->routeIs('category.*') ||  request()->routeIs('plan.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-database"></i>
            <p>
              Master Managment
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('tag.index') }}" class="nav-link  
                {{ request()->routeIs('tag.*')  ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Tag </p>
              </a>
            </li> 
          </ul>
         
         
        </li> --}}
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>