<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" type="image/png" href="{{ asset($pengaturan->logo ?? 'assets/img/logo-login/logo.png') }}?v={{ time() }}">
    <title>Keuangan - Login</title>

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/main/style.css') }}">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }} " rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/font.css') }} " rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }} " rel="stylesheet">

</head>

<body class="bg-white"> {{-- bg-login --}}
    <!-- Preloader -->
    <div class="preloader">
        <div class="spinner-border text-primary preloader-spinner" role="status">
        </div>
    </div>
    @php
        use App\Models\Setting;
            $pengaturan = Setting::first();
    @endphp

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-8 col-lg-8 col-md-9">
                <div class="text-center my-4 mb-3">
                
                <img src="{{ asset($pengaturan->logo ?? 'assets/img/logo-login/logo.png') }}?v={{ time() }}" height="150" alt="Logo Sekarang">
                </div>

                <div class="card o-hidden border-0 shadow-lg border-bottom-{{ $pengaturan->tema }}">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Login</h1>
                                    </div>
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    <hr>
                                    <form class="user" action="{{ route('login.authenticate') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text" class="form-control" value="{{ old('username') }}" name="username"
                                                id="username" placeholder="Username">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="password"
                                                id="password" placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck" onclick="togglePassword()">
                                                <label class="custom-control-label" for="customCheck">Tampilkan</label>
                                            </div>
                                        </div>
                                        <div class="form-group d-flex justify-content-center">
                                            <div style="transform: scale(0.85); transform-origin: top center;">
                                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITEKEY') }}"></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <button type="submit" class="btn btn-{{ $pengaturan->tema }} btn- btn-block">
                                            Login
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- Custom JS --}}
    <script src="{{ asset('assets/main/script.js') }}"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }} "></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }} "></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }} "></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }} "></script>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }

        // document.addEventListener('contextmenu', function(e) {
        //     e.preventDefault();
        // });
    </script>

</body>

</html>