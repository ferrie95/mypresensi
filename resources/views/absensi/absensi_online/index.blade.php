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
  <link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/select.dataTables.min.css') }}">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <script src="{{ mix('js/app.js') }}" defer></script>
  <!-- Maps -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <!-- jQuery -->
  <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
  .webcam-capture,
  .webcam-capture video{
    display: inline-block;
    width: 100% !important;
    margin: auto;
    height: auto !important;
    border-radius: 13px; 
  }

  #map{
    height: 250px;
    border-radius: 13px; 
    width: 100% !important;
    margin: auto;
    display: inline-block;
  }
</style>
<body>
  <div class="container-scroller">
    <div class="row">
      <div class="content-wrapper">
       <div class="row">
          <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Form Absensi Online</h4>
                  <form class="forms-sample">
                    <div class="form-group">
                        <label for="selectEvent">Acara</label>
                        <select class="form-control" id="selectEvent">
                          <option selected>Pilih</option>
                          <option>Meeting Mingguan Kepala Cabang</option>
                          <option>Buka Puasa Bersama Bupati Mojokerto</option>
                        </select>
                    </div>
                    <div class="form-group">
                      <input type="hidden" id="lokasi">
                      <p id="location" style="display:none;">Click the button to get your location:</p>
                      <div id="map"></div>
                    </div>
                    <div class="form-group" style="display: block;">
                      <div class="webcam-capture"></div>
                    </div>
                    <div class="form-group">
                    </div>
                    <button type="button" class="btn btn-primary mr-2" id="saveabsen">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                  </form>
                </div>
              </div>
          </div>
       </div>
      </div>
    </div>   
  </div>
  <!-- plugins:js -->
  <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
  <script src="{{ asset('assets/js/dataTables.select.min.js') }}"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
  <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('assets/js/template.js') }}"></script>
  <script src="{{ asset('assets/js/settings.js') }}"></script>
  <script src="{{ asset('assets/js/todolist.js') }}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
  <script src="{{ asset('assets/js/Chart.roundedBarCharts.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js" ></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <!-- End custom js for this page-->

  <script>
    Webcam.set({
      height:480,
      width:640,
      image_format: 'jpeg',
      jpeg_quality:80
    });

    Webcam.attach('.webcam-capture');

    document.addEventListener("DOMContentLoaded", function () {
        let lokasiInput = document.getElementById('lokasi');
        let lokasiText = document.getElementById("location");

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallBack, errorCallBack, {
                enableHighAccuracy: true,
                timeout: 10000, // Maksimal 10 detik
                maximumAge: 0
            });
        } else {
            lokasiText.innerHTML = "Geolocation tidak didukung oleh browser ini.";
        }

        function successCallBack(position) {
            let lat = position.coords.latitude;
            let lng = position.coords.longitude;
            let accuracy = position.coords.accuracy;

            lokasiInput.value = lat + "," + lng;
            lokasiText.innerHTML = `Latitude: ${lat} <br> Longitude: ${lng} <br> Akurasi: ±${accuracy} meter`;

            // Inisialisasi peta Leaflet
            let map = L.map('map').setView([lat, lng], 18);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Tambahkan marker lokasi pengguna
            let marker = L.marker([lat, lng]).addTo(map)
                .bindPopup("Lokasi Anda").openPopup();

            // Tambahkan circle untuk venue event (contoh)
            L.circle([-7.389536479459098, 112.64852063068679], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 50
            }).addTo(map).bindPopup("Venue Event");
        }

        function errorCallBack(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    lokasiText.innerHTML = "Pengguna menolak permintaan lokasi.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    lokasiText.innerHTML = "Informasi lokasi tidak tersedia.";
                    break;
                case error.TIMEOUT:
                    lokasiText.innerHTML = "Permintaan lokasi melebihi waktu tunggu.";
                    break;
                case error.UNKNOWN_ERROR:
                    lokasiText.innerHTML = "Terjadi kesalahan yang tidak diketahui.";
                    break;
            }
        }
    });

    $('#saveabsen').click(function(e){
      Webcam.snap(function(uri){
        image = uri;
      });
      var mycoordinate = $('#lokasi').val();
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        url: "absensi/absensi_online/store",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          image: image,
          coordinate: mycoordinate
        },
        cache: false,
        success: function(response){
          console.log(response);
        }
      });
    });

  </script>
</body>
</html>

