<div class="app-sidebar">
     <!-- Sidebar Logo -->
     <div class="logo-box">
          <a href="{{ route('any', 'index') }}" class="logo-dark">
               <img src="/images/logo-sm.png" class="logo-sm" alt="logo sm">
               <img src="/images/logo-dark.png" class="logo-lg" alt="logo dark">
          </a>

          <a href="{{ route('any', 'index') }}" class="logo-light">
               <img src="/images/logo-sm.png" class="logo-sm" alt="logo sm">
               <img src="/images/logo-light.png" class="logo-lg" alt="logo light">
          </a>
     </div>

     <div class="scrollbar" data-simplebar>

          <ul class="navbar-nav" id="navbar-nav">

               <li class="menu-title">Menu...</li>

               <li class="nav-item">
                    <a class="nav-link" href="{{ route('any', 'index') }}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:widget-2-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Dashboard </span>
                    </a>
               </li>

               <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.proposals') }}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:file-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Proposals </span>
                    </a>
               </li>

               <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.users.index') }}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:users-group-rounded-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Users </span>
                    </a>
               </li>

          </ul>
     </div>
</div>
