<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Register | Skydash Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">
  <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <script src="{{ mix('js/app.js') }}" defer></script>
  <!-- jQuery -->
  <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

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
              <h4>Create an Account</h4>
              <h6 class="font-weight-light">Sign up to get started.</h6>
              <form id="registerForm" class="pt-3">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                </div>
                <div class="mt-3">
                  <button type="button" id="registerButton" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN UP</button>
                </div>
                <div id="errorMessages" class="mt-3 text-danger"></div>
                <div class="text-center mt-4 font-weight-light">
                  Already have an account? <a href="{{ route('login') }}" class="text-primary">Login</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- plugins:js -->
  <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <script>
    $(document).ready(function () {
      $('#registerButton').on('click', function () {
        let name = $('#name').val();
        let email = $('#email').val();
        let password = $('#password').val();
        let password_confirmation = $('#password_confirmation').val();
        let token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
          url: "{{ route('register') }}",
          type: "POST",
          data: {
            _token: token,
            name: name,
            email: email,
            password: password,
            password_confirmation: password_confirmation,
          },
          success: function (response) {
            // SweetAlert2 Success Message
            Swal.fire({
              title: 'Registration Successful!',
              text: 'You can now login.',
              icon: 'success',
              timer: 2500
            }).then(() => {
              window.location.href = "{{ route('login') }}";
            });
          },
          error: function (xhr) {
            // SweetAlert2 Error
            let errors = xhr.responseJSON.errors;
            let errorMessage = '';
            if (errors) {
              for (let field in errors) {
                errorMessage += errors[field] + '<br>';
              }
            } else {
              errorMessage = 'Registration failed. Please try again.';
            }
            Swal.fire({
              title: 'Error!',
              html: errorMessage,
              icon: 'error',
              timer: 2500
            });
          }
        });
      });
    });
  </script>
</body>

</html>
