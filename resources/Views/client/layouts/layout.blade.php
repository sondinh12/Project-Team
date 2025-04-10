<!DOCTYPE html>
<html lang="en">
    @include('client.components.header')
<body>
    <!-- Topbar Start -->
    @include('client.components.topbar')
    <!-- Topbar End -->


    <!-- Navbar Start -->
    @include('client.components.navbar')
    <!-- Navbar End -->


    @yield('content')


    <!-- Footer Start -->
    @include('client.components.footer')
    <!-- Footer End -->


    {{-- script --}}
    @include('client.components.script')
    @yield('script')
</body>

</html>