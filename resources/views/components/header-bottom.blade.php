<div class="tp-header-bottom tp-header-bottom-border d-none d-lg-block">
    <div class="container">
       <div class="tp-mega-menu-wrapper p-relative">
          <div class="row align-items-center">
             <div class="col-xl-3 col-lg-3">
                <div class="tp-header-category tp-category-menu tp-header-category-toggle">
                   <button class="tp-category-menu-btn tp-category-menu-toggle">
                      <span>
                         <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0 1C0 0.447715 0.447715 0 1 0H15C15.5523 0 16 0.447715 16 1C16 1.55228 15.5523 2 15 2H1C0.447715 2 0 1.55228 0 1ZM0 7C0 6.44772 0.447715 6 1 6H17C17.5523 6 18 6.44772 18 7C18 7.55228 17.5523 8 17 8H1C0.447715 8 0 7.55228 0 7ZM1 12C0.447715 12 0 12.4477 0 13C0 13.5523 0.447715 14 1 14H11C11.5523 14 12 13.5523 12 13C12 12.4477 11.5523 12 11 12H1Z" fill="currentColor"/>
                         </svg>
                      </span>     
                      Tất Cả Danh Mục                           
                   </button>
                   <nav class="tp-category-menu-content">
                    <ul>
                        <li>
                           <a href="shop.html">
                              <span>
                                 <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.90532 14.8316V12.5719C5.9053 11.9971 6.37388 11.5301 6.95443 11.5262H9.08101C9.66434 11.5262 10.1372 11.9944 10.1372 12.5719V12.5719V14.8386C10.1371 15.3266 10.5305 15.7254 11.0233 15.7368H12.441C13.8543 15.7368 15 14.6026 15 13.2035V13.2035V6.77525C14.9925 6.22482 14.7314 5.70794 14.2911 5.37171L9.44253 1.50496C8.59311 0.83168 7.38562 0.83168 6.5362 1.50496L1.70886 5.37873C1.26693 5.7136 1.00544 6.23133 1 6.78227V13.2035C1 14.6026 2.1457 15.7368 3.55899 15.7368H4.97671C5.48173 15.7368 5.89114 15.3315 5.89114 14.8316V14.8316" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                 </svg>
                              </span>
                              New Arrivals</a>
                        </li>
                        <li class="has-dropdown">
                           <a href="shop.html">
                              <span>
                                 <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.5 1H2.5C1.67157 1 1 1.67157 1 2.5V10C1 10.8284 1.67157 11.5 2.5 11.5H14.5C15.3284 11.5 16 10.8284 16 10V2.5C16 1.67157 15.3284 1 14.5 1Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M5.5 14.5H11.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.5 11.5V14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                 </svg>
                              </span>
                              Computers</a>

                              <ul class="tp-submenu">
                                 <li class="has-dropdown">
                                    <a href="shop.html">Desktop</a>
                                    <ul class="tp-submenu">
                                       <li><a href="shop.html">Gaming</a></li>
                                       <li><a href="shop.html">WorkSpace</a></li>
                                       <li><a href="shop.html">Customize</a></li>
                                       <li><a href="shop.html">Luxury</a></li>
                                    </ul>
                                 </li>
                                 <li><a href="shop.html">Laptop</a></li>
                                 <li><a href="shop.html">Console</a></li>
                                 <li><a href="shop.html">Top Rated</a></li>
                              </ul>
                        </li>
                     </ul>
                   </nav>
                </div>
             </div>
             <div class="col-xl-6 col-lg-6">
                <div class="main-menu menu-style-1">
                   <nav class="tp-main-menu-content">
                      <ul>
                         <li><a href="contact.html">Contact</a></li>
                      </ul>
                   </nav>
                </div>
             </div>
          </div>
       </div>
    </div>
 </div>

 <div x-data id="header-sticky-2" class="tp-header-sticky-area">
   <div class="container">
      <div class="tp-mega-menu-wrapper p-relative">
         <div class="row align-items-center">
            <div class="col-xl-3 col-lg-3 col-md-3 col-6">
               <div class="logo">
                  <a href="/">
                     <img src="{{asset('assets/img/logo/logo.svg')}}" alt="logo">
                  </a>
               </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 d-none d-md-block">
               <div class="tp-header-sticky-menu main-menu menu-style-1">
                  <nav id="mobile-menu"> 
                     <ul>                              
                        <li><a href="contact.html">Contact</a></li>
                     </ul>
                  </nav>
               </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-6">
               <div class="tp-header-action d-flex align-items-center justify-content-end ml-50">
                  <div class="tp-header-action-item">
                     <button @click="openCart($store)" type="button" class="tp-header-action-btn cartmini-open-btn">
                        <svg width="21" height="22" viewBox="0 0 21 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                           <path fill-rule="evenodd" clip-rule="evenodd" d="M6.48626 20.5H14.8341C17.9004 20.5 20.2528 19.3924 19.5847 14.9348L18.8066 8.89359C18.3947 6.66934 16.976 5.81808 15.7311 5.81808H5.55262C4.28946 5.81808 2.95308 6.73341 2.4771 8.89359L1.69907 14.9348C1.13157 18.889 3.4199 20.5 6.48626 20.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                           <path d="M6.34902 5.5984C6.34902 3.21232 8.28331 1.27803 10.6694 1.27803V1.27803C11.8184 1.27316 12.922 1.72619 13.7362 2.53695C14.5504 3.3477 15.0081 4.44939 15.0081 5.5984V5.5984" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                           <path d="M7.70365 10.1018H7.74942" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                           <path d="M13.5343 10.1018H13.5801" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>    
                        <span class="tp-header-action-badge" x-show="$store.cart.items.length" x-text="$store.cart.items.length"></span>                                                                          
                     </button>
                  </div>
                  <div class="tp-header-action-item d-lg-none">
                     <button type="button" class="tp-header-action-btn tp-offcanvas-open-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="16" viewBox="0 0 30 16">
                           <rect x="10" width="20" height="2" fill="currentColor"/>
                           <rect x="5" y="7" width="25" height="2" fill="currentColor"/>
                           <rect x="10" y="14" width="20" height="2" fill="currentColor"/>
                        </svg>
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>