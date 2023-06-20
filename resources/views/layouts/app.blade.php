<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Laundry-In</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('/images/logo.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('/images/logo.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('/images/logo.png') }}" />

    {{-- CSS --}}
    @yield('css')
    {{-- END CSS --}}

  </head>
  <body>

  <div class="site-wrap">

    {{-- HEADER --}}
    @include('layouts.partials.header')
    {{-- END HEADER --}}

    {{-- CONTENT --}}
    @yield('content')
    {{-- END CONTENT --}}

    {{-- FOOTER --}}
    @include('layouts.partials.footer')
    {{-- END FOOTER --}}

  </div>

  {{-- JS --}}
  @yield('js')
  {{-- END JS --}}

  {{-- SCRIPT --}}
  @yield('script')
  {{-- END SCRIPT --}}

  </body>
</html>
