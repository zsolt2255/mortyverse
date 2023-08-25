@include('layouts/header')

<body>
<div class="container-fluid">
    @yield('buttons')
    @yield('table')
</div>

@include('layouts/footer')

@yield('scripts')
</body>
</html>
