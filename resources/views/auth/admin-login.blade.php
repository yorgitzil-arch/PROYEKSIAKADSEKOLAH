<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $schoolProfile->name ?? 'nama sekolah'}}| Login Admin</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

    <style>
        /* Universal box-sizing for easier layout calculations */
        *, *::before, *::after {
            box-sizing: border-box;
        }

        /* Custom CSS for Login Page */
        body.login-page {
            background-image: url("{{ asset('assetsmk/bacground3.jpg') }}");
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px; /* Add some padding around the box itself */
            overflow-y: auto; /* Allow vertical scrolling if content is too large */
            overflow-x: hidden; /* Prevent horizontal scrolling */
        }

        .login-box {
            width: 900px;
            max-width: 95%; /* Ensures it scales down on very small screens */
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            border-radius: 15px;
            overflow: hidden; /* Crucial for containing rounded corners */
            display: flex;
            /* MODIFIKASI INI: Kurangi nilai opasitas dari 0.9 menjadi, misalnya, 0.7 atau 0.8 */
            background-color: rgba(255, 255, 255, 0.75); /* Contoh: 75% opasitas */
            min-height: 500px;
        }

        .login-left-panel {
            flex: 1; /* Allow left panel to take available space */
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #343a40;
            text-align: center;
            position: relative;
            /* MODIFIKASI INI: Kurangi nilai opasitas pada gradient juga jika Anda ingin panel kiri juga lebih transparan */
            background: linear-gradient(to bottom right, rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.55)); /* Contoh: 75% dan 55% opasitas */
            min-width: 0; /* Allow flex item to shrink below content size */
        }

        .login-left-panel .logo-container {
            margin-bottom: 30px;
        }

        .login-left-panel .logo-school {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid rgba(0, 123, 255, 0.6);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .login-left-panel .logo-school:hover {
            transform: scale(1.05);
        }

        .login-left-panel .welcome-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%; /* Ensure content takes full width within its panel */
            padding-bottom: 0;
        }

        .login-left-panel h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #007bff;
            word-wrap: break-word; /* Prevent long words from overflowing */
            max-width: 100%; /* Ensure heading doesn't exceed its container */
        }

        .login-left-panel p.system-description {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #495057;
            margin-top: 5px;
            word-wrap: break-word; /* Prevent long words from overflowing */
            max-width: 100%; /* Ensure paragraph doesn't exceed its container */
        }

        .login-right-panel {
            width: 450px; /* Fixed width for desktop */
            min-width: 300px; /* Minimum width for desktop to prevent crushing */
            padding: 40px;
            /* MODIFIKASI INI: Tetapkan background-color putih solid atau sesuaikan opasitas jika diinginkan */
            background-image:linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('assetsmk/bacground2.jpg') }}');
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .login-logo {
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: 700;
            color: #fcfcfcff;
            text-align: center;
            width: 100%;
        }

        .login-logo b {
            color: #007bff;
        }

        .login-box-msg {
            font-size: 1.1rem;
            color: #428bcaff;
            margin-bottom: 25px;
            text-align: center;
            padding-top:10px;
        }

        .login-card-body {
            padding: 0; /* Remove default card body padding */
            width: 100%; /* Ensure it takes full width of its parent */
            border-radius:10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
            background-color: rgba(54, 54, 54, 0.9);
        }
        .input-group {
            margin-bottom: 20px;
            color: #ffffffff;
            width: 90%;
            margin-left:5%;
         /* Tambah jarak antar input groups */
        }

        .input-group .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            height: auto;
            border: 1px solid #ced4da;
            box-shadow: 0 0px 5px rgba(0, 0, 0, 0.5);
            background-color: rgba(255, 255, 255, 0.7);
            
        }

        .input-group-append .input-group-text {
            border-radius: 0 8px 8px 0;
            background-color: #e9ecef;
            border-left: none;
            border: 1px solid #ced4da;
            padding: 0.75rem 1rem;
        }

        .btn-primary {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3);
            background: linear-gradient(45deg, #0056b3, #007bff);
        }

        .icheck-primary label {
            font-size: 0.95rem;
            color: #fdfdfdff;
        }

        .mb-1 a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 500;
            margin-left:20px;
        }

        .mb-1 a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .login-box {
                flex-direction: column; /* Stack panels vertically */
                width: 100%; /* Take full width of its parent (body padding applied) */
                max-width: 450px; /* Limit max-width for aesthetic purposes on tablets */
                margin: 20px auto; /* Center the box and add vertical margin */
                min-height: auto; /* Allow height to adjust */
            }

            /* Hide the left panel (logo and welcome message) on smaller screens */
            .login-left-panel {
                display: none; /* This is the key change to hide the left panel */
            }

            .login-right-panel {
                width: 100%; /* Take full width when stacked */
                padding: 30px; /* Adjust padding for mobile */
                order: 2; /* Ensure right panel comes second */
                border-radius: 15px; /* Apply full border-radius since it's now the only panel */
                min-width: unset; /* Remove min-width when stacked */
                /* MODIFIKASI INI: Jika panel kiri disembunyikan, panel kanan menjadi satu-satunya bagian. */
                /* Anda mungkin ingin membuat panel kanan juga transparan agar terlihat lebih kohesif */
                background-color: rgba(255, 255, 255, 0.75); /* Contoh: 75% opasitas untuk panel kanan saat mobile */
            }

            .login-logo {
                font-size: 1.8rem;
                margin-bottom: 20px;
            }

            .login-box-msg {
                font-size: 1rem;
                margin-bottom: 20px;
            }
        }

        @media (max-width: 576px) {
            .login-box {
                width: calc(100% - 20px); /* Full width with 10px padding on each side from body */
                margin: 10px auto; /* Small margin from edges */
            }

            /* No need to hide .login-left-panel here, as it's already hidden by the 992px media query */

            .login-right-panel {
                padding: 20px; /* Reduce padding further on very small screens */
            }

            /* The left panel (logo and welcome message) is already hidden on screens <= 992px,
               so these styles for its elements are not strictly necessary here,
               but keeping them for consistency with the desktop view if it were ever to be re-enabled. */
            .login-left-panel h2 {
                font-size: 2rem; /* Smaller font for title */
            }

            .login-left-panel p.system-description {
                font-size: 1rem; /* Smaller font for description */
            }
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-left-panel">
        @if($schoolProfile->logo_path)
        <div class="logo-container">
            <img src="{{ asset('storage/' . $schoolProfile->logo_path) }}" alt="Logo Sekolah" class="logo-school">
        </div>
        @endif
        <div class="welcome-content">
            <h2>Selamat Datang</h2>
            <p class="system-description">Di Sistem Akademik <span style="background-color:blue; padding:4px; color:white; font-size:16px;">{{ $schoolProfile->name }}</span> Silakan masuk untuk mengakses fitur-fitur admin</p>
        </div>
    </div>
    <div class="login-right-panel">
        <div class="login-logo">
            <a href="#" style="color:white;"><b>Admin</b> {{ $schoolProfile->name }}</a>
        </div>
        <div class="card-body login-card-body">
            <p class="login-box-msg">Masuk sebagai Admin</p>

            <form action="{{ route('admin.login.post') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
                    @enderror
                </div>
                <div class="row" style="margin:0px 10px 10px 10px;">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">
                                Ingat Saya
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                    </div>
                </div>
            </form>

            <p class="mb-1">
                <a href="#">Saya lupa password</a>
            </p>
        </div>
    </div>
</div>
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
