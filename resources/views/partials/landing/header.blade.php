 <!-- Header Section Begin -->
 <header class="header-section">
     <!-- <div class="top-nav">
        <div class="container">
          <div class="row">
            <div class="col-lg-6">
              <ul class="tn-left">
                <li><i class="fa fa-phone"></i> (12) 345 67890</li>
                <li><i class="fa fa-envelope"></i> info.colorlib@gmail.com</li>
              </ul>
            </div>
            <div class="col-lg-6">
              <div class="tn-right">
                <div class="top-social">
                  <a href="#"><i class="fa fa-facebook"></i></a>
                  <a href="#"><i class="fa fa-twitter"></i></a>
                  <a href="#"><i class="fa fa-tripadvisor"></i></a>
                  <a href="#"><i class="fa fa-instagram"></i></a>
                </div>
                <a href="#" class="bk-btn">Booking Now</a>
                <div class="language-option">
                  <img src="img/flag.jpg" alt="" />
                  <span>EN <i class="fa fa-angle-down"></i></span>
                  <div class="flag-dropdown">
                    <ul>
                      <li><a href="#">Zi</a></li>
                      <li><a href="#">Fr</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> -->
     <div class="menu-item">
         <div class="container">
             <div class="row">
                 <div class="col-lg-2">
                     <div class="logo d-flex align-items-center gap-2">
                         <a href="#" class="d-flex align-items-center gap-2 text-decoration-none">
                             <img src="{{ asset('admin/assets/images/bella.png') }}" class="img-fluid"
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
                         {{-- <div class="nav-right search-switch">
                             <i class="icon_search"></i>
                         </div> --}}
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </header>
 <!-- Header End -->
