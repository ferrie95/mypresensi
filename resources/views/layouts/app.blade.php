<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Skydash | {{ $title }}</title>
  <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />

  @include('partials.css')
  @yield('css')
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container-scroller">
        @include('partials.navbar')
        <div class="container-fluid page-body-wrapper">
            @include('partials.side_menu')
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('dashboard')
                    @yield('container')
                </div>
                @include('partials.footer')
            </div>
        </div>   
    </div>
  @include('partials.scripts')
  @yield('script')
</body>
<script>
$(document).ready(function () {
  $('#logoutButton').on('click', function () {
      // Ambil CSRF Token
      var token = $('meta[name="csrf-token"]').attr('content');

      // AJAX request
      $.ajax({
          url: "{{ route('logout') }}",
          type: "POST",
          data: {
              _token: token
          },
          success: function (response) {
            window.location.href = '/login'; // Redirect to login page
          },
          error: function (xhr, status, error) {
              Swal.fire({
                  title: 'Error',
                  text: 'Something went wrong, please try again.',
                  icon: 'error'
              });
          }
      });
  });
});
</script>
</html>

