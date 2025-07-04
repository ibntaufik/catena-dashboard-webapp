<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="Catena Dashboard">
	<meta name="author" content="Cognizest">
	<meta name="keywords" content="catena">

  <title>Catena Dashboard</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
  <!-- End fonts -->
  
  <!-- CSRF Token -->
  <meta name="_token" content="{{ csrf_token() }}">
  
  <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">

  <!-- plugin css -->
  <link href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2-4.1.0/dist/css/select2.min.css') }}" rel="stylesheet" />

  <!-- end plugin css -->

  @stack('plugin-styles')

  <!-- common css -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
  <!-- end common css -->

  @stack('style')

  <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
  <script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(async function(OneSignal) {
      await OneSignal.init({
        appId: "6e35f10b-f0b4-40c4-b7ca-a0b988f38cfc",
      });
    });
  </script>
</head>
<body data-base-url="{{url('/')}}" class="sidebar-dark">

  <script src="{{ asset('assets/js/spinner.js') }}"></script>

  <div class="main-wrapper" id="app">
    @include('layout.sidebar')
    <div class="page-wrapper">
      @include('layout.header')
      <div class="page-content">
        @yield('content')
      </div>
      @include('layout.footer')
    </div>
  </div>

    <!-- base js -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2-4.1.0/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/numeral/min/numeral.min.js') }}"></script>

    <!-- end base js -->

    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->

    <!-- common js -->
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <!-- end common js -->
    <!-- notify js -->
    <script src="{{ asset('assets/js/notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/notify.js') }}"></script>

    <script src="{{ asset('assets/js/validation-regex.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    @stack('custom-scripts')
</body>
@yield('javascript', '')
</html>