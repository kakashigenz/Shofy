<x-cart/>

<!-- header area start -->
<header>
    <div class="tp-header-area p-relative z-index-11">
       <!-- header top start  -->
       <div class="tp-header-top black-bg p-relative z-index-1 d-none d-md-block">
          <div class="container">
             <div class="row align-items-center">
                <div class="col-md-6">
                   <div class="tp-header-welcome d-flex align-items-center">
                      <span>
                         <svg width="22" height="19" viewBox="0 0 22 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.6364 1H1V12.8182H14.6364V1Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14.6364 5.54545H18.2727L21 8.27273V12.8182H14.6364V5.54545Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5.0909 17.3636C6.3461 17.3636 7.36363 16.3461 7.36363 15.0909C7.36363 13.8357 6.3461 12.8182 5.0909 12.8182C3.83571 12.8182 2.81818 13.8357 2.81818 15.0909C2.81818 16.3461 3.83571 17.3636 5.0909 17.3636Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.9091 17.3636C18.1643 17.3636 19.1818 16.3461 19.1818 15.0909C19.1818 13.8357 18.1643 12.8182 16.9091 12.8182C15.6539 12.8182 14.6364 13.8357 14.6364 15.0909C14.6364 16.3461 15.6539 17.3636 16.9091 17.3636Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                         </svg>                                 
                      </span>
                      <p>Vận chuyển nhanh chóng</p>
                   </div>
                </div>
                <div x-data class="col-md-6">
                   <div class="tp-header-top-right d-flex align-items-center justify-content-end">
                      <div class="tp-header-top-menu d-flex align-items-center justify-content-end">
                        @if (auth()->guard('sanctum')->check())
                           <div class="tp-header-top-menu-item tp-header-setting">
                              <img src="{{asset('assets/images/tanjiro.jpg')}}" alt="" class="user-avatar">
                              <span class="tp-header-setting-toggle" id="tp-header-setting-toggle">{{auth('sanctum')->user()->name}}</span>
                              <ul>
                                 <li>
                                    <a href="{{route('profile')}}">Thông Tin</a>
                                 </li>
                                 <li>
                                    <button @click="logout()">Đăng Xuất</button>
                                 </li>
                              </ul>
                           </div>
                        @else
                           <div class="tp-header-top-menu-item tp-header-setting">
                              <a href="{{route('login')}}" class="auth-btn">Đăng nhập</a> | 
                              <a href="#" class="auth-btn">Đăng ký</a>
                           </div>
                        @endif
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>

       <!-- header main start -->
       <div class="tp-header-main tp-header-sticky">
          <div class="container">
             <div class="row align-items-center">
                <div class="col-xl-2 col-lg-2 col-md-4 col-6">
                   <div class="logo">
                      <a href="/">
                         <img src="{{asset('assets/img/logo/logo.svg')}}" alt="logo">
                      </a>
                   </div>
                </div>
                <div class="col-xl-6 col-lg-7 d-none d-lg-block">
                   <div class="tp-header-search pl-70">
                      <form action="#">
                         <div class="tp-header-search-wrapper d-flex align-items-center">
                            <div class="tp-header-search-box">
                               <input type="text" placeholder="Tìm kiếm sản phẩm">
                            </div>
                            <div class="tp-header-search-btn">
                               <button type="submit">
                                  <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                     <path d="M9 17C13.4183 17 17 13.4183 17 9C17 4.58172 13.4183 1 9 1C4.58172 1 1 4.58172 1 9C1 13.4183 4.58172 17 9 17Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                     <path d="M19 19L14.65 14.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                  </svg>                                          
                               </button>
                            </div>
                         </div>
                      </form>
                   </div>
                </div>
                <div class="col-xl-4 col-lg-3 col-md-8 col-6">
                   <div class="tp-header-main-right d-flex align-items-center justify-content-end">
                      <div class="tp-header-action d-flex align-items-center ml-50">
                         <div x-data class="tp-header-action-item">
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
    </div>
 </header>
 <!-- header area end -->
 @push('styles')
   <style>
         .auth-btn{
            color: #fff;
            font-size: 12px;
            font-weight: 600;
         }

         .user-avatar{
            height: 24px;
            border-radius: 50%;
            object-fit: cover;
            object-position: center;
         }
   </style>
 @endpush
 @push('scripts')
    <script>
          $.ajaxSetup({
            headers:{
               Authorization: `Bearer ${getCookie('token')}`
            }
         })

         document.addEventListener('alpine:init',() => {
            Alpine.data('data',() => ({
               items: []
            }))

            Alpine.store('cart',{
               items: [],
               printPrice(price){
                  const formater = Intl.NumberFormat('vi-VN',{style: 'currency',currency: 'vnd'})
                  return formater.format(price)
               },
               loadCart() {
                  const that = this
                  $.ajax({
                     url: '/api/cart',
                     method: 'get',
                     success: function (res) {
                        that.items = res.items
                     }
                  })
               },
               init(){
                  this.loadCart()
               }
            })

            
         })

         function openCart(store) {
            //call api here
            $.ajax({
               url: '/api/cart',
               method: 'get',
               success: function (res) {
                  store.cart.items = res.items
               },
               complete: function () {
                  //open cart
                  $(".cartmini__area").addClass("cartmini-opened");
                  $(".body-overlay").addClass("opened");
               }
            })
            
         }

         function getCookie(name) {
            const cookies = document.cookie.split(";");
            const cookie = cookies.find((item) => item.trim().startsWith(name));
            let res = ''
            if (cookie){
               const start = cookie.indexOf("=");

               res = cookie.substring(start + 1);
            }
            return res
         }

         function deleteCookie(name) {
            const cookies = document.cookie.split(";");
            const cookie = cookies.find((item) => item.trim().startsWith(name));
            if (cookie){
               const [name,value] = cookie.split("=");
               const expires = new Date()
               expires.setDate(expires.getDate() - 1)
               document.cookie = `${name}=;expires =${expires.toUTCString()}; path=/;`
            }
         }

         function logout() {
            $.ajax({
               url: '/api/logout',
               method: 'get',
               success: function (res) {
                  if (res.message = 'logged out'){
                     deleteCookie('token')
                     window.location.href = '/'
                  }
               }
            })
         }
    </script>
@endpush
