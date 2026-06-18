 <!-- Header Section Begin -->
 <header class="header-section">
     <div class="menu-item">
         <div class="container">
             <div class="row">
                 <div class="col-lg-2">
                     <div class="logo d-flex align-items-center gap-2">
                         <a href="#" class="d-flex align-items-center gap-2 text-decoration-none">
                             <img src="{{ asset('admin/assets/images/logo.jpeg') }}" class="img-fluid"
                                 alt="Bellamonte Resort App Logo" style="height: 60px; width: auto;">

                             <h4 class="fw-bold text-dark mb-0 sd" style="white-space: nowrap; font-size: 20px;">
                                 Bellamonte Resort
                             </h4>
                             <h4 class="fw-bold fs-5 text-dark ds">
                                 BM Resort
                             </h4>
                         </a>
                     </div>
                 </div>
                 <div class="col-lg-10">
                     <div class="nav-menu">
                         <nav class="mainmenu">
                             <ul>
                                 <li class="{{ request()->routeIs('welcomepage') ? 'active' : '' }}">
                                     <a href="{{ route('welcomepage') }}">Home</a>
                                 </li>

                                 <li class="{{ request()->routeIs('rooms.list') ? 'active' : '' }}">
                                     <a href="{{ route('rooms.list') }}">Rooms</a>
                                 </li>

                                 <li class="{{ request()->routeIs('events.list') ? 'active' : '' }}">
                                     <a href="{{ route('events.list') }}">Events</a>
                                 </li>

                                 <li class="{{ request()->routeIs('about.us') ? 'active' : '' }}">
                                     <a href="{{ route('about.us') }}">About Us</a>
                                 </li>

                                 <li class="{{ request()->routeIs('contact.us') ? 'active' : '' }}">
                                     <a href="{{ route('contact.us') }}">Contact</a>
                                 </li>
                             </ul>
                         </nav>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </header>
 <!-- Header End -->
