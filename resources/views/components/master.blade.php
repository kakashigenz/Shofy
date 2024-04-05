@props(['title'])
<!doctype html>
<html class="no-js" lang="zxx">
   
<head>
      <meta charset="utf-8">
      <meta http-equiv="x-ua-compatible" content="ie=edge">
      <title>{{$title}}</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">

      <!-- Place favicon.ico in the root directory -->
      <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/img/logo/favicon.png')}}">

      <!-- CSS here -->
      <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/animate.css')}}">
      <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.css')}}">
      <link rel="stylesheet" href="{{asset('assets/css/slick.css')}}">
      <link rel="stylesheet" href="{{asset('assets/css/magnific-popup.css')}}">
      <link rel="stylesheet" href="{{asset('assets/css/font-awesome-pro.css')}}">
      <link rel="stylesheet" href="{{asset('assets/css/flaticon_shofy.css')}}">
      <link rel="stylesheet" href="{{asset('assets/css/spacing.css')}}">
      <link rel="stylesheet" href="{{asset('assets/css/select2.css')}}">
      <link rel="stylesheet" href="{{asset('assets/css/toastr.css')}}">
      <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
      @stack('styles')
      <script defer src="{{asset('assets/js/vendor/alpine.js')}}"></script>
   </head>
   <body>
      
      {{$slot}}

      <!-- JS here -->
      <script src="{{asset('assets/js/vendor/jquery.js')}}"></script>
      <script src="{{asset('assets/js/vendor/waypoints.js')}}"></script>
      <script src="{{asset('assets/js/bootstrap-bundle.js')}}"></script>
      <script src="{{asset('assets/js/meanmenu.js')}}"></script>
      <script src="{{asset('assets/js/swiper-bundle.js')}}"></script>
      <script src="{{asset('assets/js/slick.js')}}"></script>
      <script src="{{asset('assets/js/range-slider.js')}}"></script>
      <script src="{{asset('assets/js/magnific-popup.js')}}"></script>
      <script src="{{asset('assets/js/nice-select.js')}}"></script>
      <script src="{{asset('assets/js/purecounter.js')}}"></script>
      <script src="{{asset('assets/js/countdown.js')}}"></script>
      <script src="{{asset('assets/js/wow.js')}}"></script>
      <script src="{{asset('assets/js/isotope-pkgd.js')}}"></script>
      <script src="{{asset('assets/js/imagesloaded-pkgd.js')}}"></script>
      <script src="{{asset('assets/js/ajax-form.js')}}"></script>
      <script src="{{asset('assets/js/vendor/select2.js')}}"></script>
      <script src="{{asset('assets/js/vendor/jquery-validation.js')}}"></script>
      <script src="{{asset('assets/js/vendor/bootbox.min.js')}}"></script>
      <script src="{{asset('assets/js/vendor/toastr.js')}}"></script>
      <script src="{{asset('assets/js/main.js')}}"></script>
      @stack('scripts')
   </body>

</html>
