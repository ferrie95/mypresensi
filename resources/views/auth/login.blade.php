<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Skydash Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">
  <!-- endinject -->
  <!-- Stylesheets -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
  {{-- <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <script src="{{ mix('js/app.js') }}" defer></script> --}}
  
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
  .swal2-popup {
      font-family: 'Arial', sans-serif !important; /* Atur font untuk menghindari konflik */
  }

  .swal2-styled {
      padding: 10px 20px !important; /* Pastikan padding tombol sesuai */
      font-size: 14px !important; /* Sesuaikan ukuran font */
  }
</style>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
              </div>
              <h4>Hello! let's get started</h4>
              <h6 class="font-weight-light">Sign in to continue.</h6>
              <form id="loginForm" class="pt-3">
                @csrf
                <div class="form-group">
                  <input type="number" class="form-control form-control-lg" id="nik" name="nik" placeholder="NIP" required>
                </div>
                {{-- <div class="form-group">
                  <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email" required>
                </div> --}}
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="mt-3">
                  <button type="button" id="loginButton" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                </div>
                <div id="errorMessages" class="mt-3 text-danger"></div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  {{-- <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input"> Keep me signed in
                    </label>
                  </div> --}}
                  {{-- <a href="#" class="auth-link text-black">Forgot password?</a> --}}
                </div>
                {{-- <div class="text-center mt-4 font-weight-light">
                  Don't have an account? <a href="{{ route('register') }}" class="text-primary">Create</a>
                </div> --}}
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
  <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('assets/js/template.js') }}"></script>
  <script src="{{ asset('assets/js/settings.js') }}"></script>
  <script src="{{ asset('assets/js/todolist.js') }}"></script>
  <!-- endinject -->
  <!-- jQuery -->
  <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
  <!-- Sweet Alert 2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script>
      $(document).ready(function () {

        // Fungsi untuk login saat tombol 'Login' diklik
        $('#loginButton').on('click', function () {
            login();
        });

        // Fungsi untuk login saat tombol 'Enter' ditekan di form
        $('#nik, #password').on('keypress', function (e) {
            if (e.which == 13) { // Jika tombol Enter ditekan (kode key 13)
                login();
            }
        });

        // Fungsi login yang digunakan baik saat tombol 'Login' diklik atau Enter ditekan
        function login() {
            let nik = $('#nik').val(); // Ambil nilai NIK
            let password = $('#password').val(); // Ambil nilai password
            let remember = $('#remember').is(':checked') ? 1 : 0; // Jika ada opsi remember me

            // Validasi input
            if (nik == '') {
                Swal.fire({
                    title: 'Warning !',
                    text: 'Harap Isi Kolom NIP.',
                    icon: 'warning',
                });
            } else if (password == '') {
                Swal.fire({
                    title: 'Warning !',
                    text: 'Harap Isi Kolom Password.',
                    icon: 'warning',
                });
            } else {
                // Setup Ajax
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Kirim data ke server
                $.ajax({
                    url: "/login", // Ganti ini jika route login Anda berbeda
                    type: "POST",
                    data: {
                        nik: nik, // Kirimkan NIP
                        password: password,
                        remember: remember
                    },
                    success: function (response) {
                        window.location.href = response.redirect; // Jika login berhasil, redirect ke dashboard
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON.message || 'Invalid NIP or password.', // Pesan error jika login gagal
                            icon: 'error',
                        });
                    }
                });
            }
        }

      });

  </script>
</body>

</html>
