@props(['title'])
<x-master :title="$title">
    <!--[if lte IE 9]>
      <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

      @include('partials._loading')

      @include('partials._backtotop')

      <!-- offcanvas area start -->
      <div class="offcanvas__area offcanvas__radius">
         <div class="offcanvas__wrapper">
            <div class="offcanvas__close">
               <button class="offcanvas__close-btn offcanvas-close-btn">
                  <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                     <path d="M11 1L1 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                     <path d="M1 1L11 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                 </svg>
               </button>
            </div>
            <div class="offcanvas__content">
               <div class="offcanvas__top mb-70 d-flex justify-content-between align-items-center">
                  <div class="offcanvas__logo logo">
                     <a href="index.html">
                        <img src="{{asset('assets/img/logo/logo.svg')}}" alt="logo">
                     </a>
                  </div>
               </div>
               <div class="offcanvas__category pb-40">
                  <button class="tp-offcanvas-category-toggle">
                     <i class="fa-solid fa-bars"></i>
                     Tất Cả Danh Mục
                  </button>
                  <div class="tp-category-mobile-menu">
                     
                  </div>
               </div>
               <div class="tp-main-menu-mobile fix d-lg-none mb-40"></div>
            </div>
         </div>
      </div>
      <div class="body-overlay"></div>
      <!-- offcanvas area end -->

      <x-mobile-menu />

      <x-search-mobile/>      

       {{$slot}}
      

      <!-- footer area start -->
      <footer>
         <div class="tp-footer-area" data-bg-color="footer-bg-grey">
            <div class="tp-footer-top pt-95 pb-40">
               <div class="container">
                  <div class="row">
                     <div class="col-xl-4 col-lg-3 col-md-4 col-sm-6">
                        <div class="tp-footer-widget footer-col-1 mb-50">
                           <div class="tp-footer-widget-content">
                              <div class="tp-footer-logo">
                                 <a href="index.html">
                                    <img src="{{asset('assets/img/logo/logo.svg')}}" alt="logo">
                                 </a>
                              </div>
                              <p class="tp-footer-desc">Mang lại cho bạn trải nghiệm mua sắm tuyệt vời nhất</p>
                              <div class="tp-footer-social">
                                 <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                                 <a href="#"><i class="fa-brands fa-twitter"></i></a>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="tp-footer-widget footer-col-2 mb-50">
                           <h4 class="tp-footer-widget-title">Hỗ trợ khách hàng</h4>
                           <div class="tp-footer-widget-content">
                              <ul>
                                 <li><a href="#">Các câu hỏi thường gặp</a></li>
                                 <li><a href="#">Gửi yêu cầu hỗ trợ</a></li>
                                 <li><a href="#">Hướng dẫn đặt hàng</a></li>
                                 <li><a href="#">Chính sách hàng nhập khẩu</a></li>
                                 <li><a href="#">Phương thức vận chuyển</a></li>
                                 <li><a href="#">Hướng dẫn trả góp</a></li>
                              </ul>
                           </div>
                        </div>
                     </div>
                     <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                        <div class="tp-footer-widget footer-col-3 mb-50">
                           <h4 class="tp-footer-widget-title">Thông tin</h4>
                           <div class="tp-footer-widget-content">
                              <ul>
                                 <li><a href="#">Về chúng tôi</a></li>
                                 <li><a href="#">Sự nghiệp</a></li>
                                 <li><a href="#">Chính sách bảo mật</a></li>
                                 <li><a href="#">Điều kiện và điều khoản</a></li>
                                 <li><a href="#">Tin mới</a></li>
                                 <li><a href="#">Liên hệ</a></li>
                              </ul>
                           </div>
                        </div>
                     </div>
                     <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                        <div class="tp-footer-widget footer-col-4 mb-50">
                           <h4 class="tp-footer-widget-title">Góp ý cho chúng tôi</h4>
                           <div class="tp-footer-widget-content">
                              <div class="tp-footer-talk mb-20">
                                 <span>Nếu bạn có thắc mắc hãy liên hệ với chúng tôi bằng cách</span>
                              </div>
                              <div class="tp-footer-contact">
                                 <div class="tp-footer-contact-item d-flex align-items-start">
                                    <div class="tp-footer-contact-icon">
                                       <span>
                                          <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                             <path d="M1 5C1 2.2 2.6 1 5 1H13C15.4 1 17 2.2 17 5V10.6C17 13.4 15.4 14.6 13 14.6H5" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                             <path d="M13 5.40039L10.496 7.40039C9.672 8.05639 8.32 8.05639 7.496 7.40039L5 5.40039" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                             <path d="M1 11.4004H5.8" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                             <path d="M1 8.19922H3.4" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                          </svg>
                                       </span>
                                    </div>
                                    <div class="tp-footer-contact-content">
                                       <p><a href="#">shopfy@support.com</a></p>
                                    </div>
                                 </div>
                                 <div class="tp-footer-contact-item d-flex align-items-start">
                                    <div class="tp-footer-contact-icon">
                                       <span>
                                          <svg width="17" height="20" viewBox="0 0 17 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                             <path d="M8.50001 10.9417C9.99877 10.9417 11.2138 9.72668 11.2138 8.22791C11.2138 6.72915 9.99877 5.51416 8.50001 5.51416C7.00124 5.51416 5.78625 6.72915 5.78625 8.22791C5.78625 9.72668 7.00124 10.9417 8.50001 10.9417Z" stroke="currentColor" stroke-width="1.5"/>
                                             <path d="M1.21115 6.64496C2.92464 -0.887449 14.0841 -0.878751 15.7889 6.65366C16.7891 11.0722 14.0406 14.8123 11.6313 17.126C9.88298 18.8134 7.11704 18.8134 5.36006 17.126C2.95943 14.8123 0.210885 11.0635 1.21115 6.64496Z" stroke="currentColor" stroke-width="1.5"/>
                                          </svg>
                                       </span>
                                    </div>
                                    <div class="tp-footer-contact-content">
                                       <p><a href="https://www.google.com/maps/place/University+of+Transport+and+Communications/@21.0283229,105.807143,16.6z/data=!4m6!3m5!1s0x3135ab424a50fff9:0xbe3a7f3670c0a45f!8m2!3d21.0281545!4d105.8034205!16s%2Fm%2F0vb3l_2?entry=ttu" target="_blank">Hà Nội,Việt Nam</a></p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="tp-footer-bottom">
               <div class="container">
                  <div class="tp-footer-bottom-wrapper">
                     <div class="row align-items-center">
                        <div class="col-md-6">
                           <div class="tp-footer-copyright">
                              <p>© {{date('Y')}} Bản quyền thuộc Shopfy.</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </footer>
      <!-- footer area end -->
</x-master>
