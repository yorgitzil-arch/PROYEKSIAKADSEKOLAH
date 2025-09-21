<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $schoolProfile->name ?? 'nama sekolah'}} | Login Siswa</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* Universal box-sizing for easier layout calculations */
        *, *::before, *::after {
            box-sizing: border-box;
        }

        /* Custom CSS for Login Page */
        body.login-page {
        
            background-image:linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.1)), url("{{ asset('assetsmk/bacground3.jpg') }}");
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            overflow-y: auto; 
            overflow-x: hidden; 
        }

        .login-box {
            width: 900px; /* Lebar keseluruhan kontainer login */
            max-width: 95%; /* Pastikan responsif */
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3); /* Bayangan lebih gelap untuk kontras */
            border-radius: 15px;
            overflow: hidden;
            display: flex; /* Menggunakan flexbox untuk layout split */
            background-color: rgba(255, 255, 255, 0.9); /* Sedikit transparan putih */
            min-height: 500px;
        }

        .login-left-panel {
            flex: 1; /* Ambil sisa ruang */
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Pusatkan konten vertikal di panel kiri */
            align-items: center;    /* Pusatkan konten horizontal di panel kiri */
            color: #343a40; /* Warna teks yang lebih gelap untuk kontras dengan background transparan */
            text-align: center; /* Teks juga akan terpusat */
            position: relative;
            background: linear-gradient(to bottom right, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7)); /* Latar belakang semi-transparan putih */
            transition: all 0.3s ease-in-out; /* Smooth transition for hiding */
        }

        .login-left-panel .logo-container {
            margin-bottom: 30px; /* Space between logo and Welcome text */
        }

        .login-left-panel .logo-school {
            width: 150px; /* Ukuran logo lebih besar */
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid rgba(0, 123, 255, 0.6); /* Border biru untuk logo */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .login-left-panel .logo-school:hover {
            transform: scale(1.05); /* Zoom effect on hover */
        }

        .login-left-panel .welcome-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding-bottom: 0;
        }

        .login-left-panel h2 {
            font-size: 2.5rem; /* Larger font size for "Selamat Datang" */
            font-weight: 700;
            margin-bottom: 10px;
            color: #007bff; /* Blue color for main title */
        }

        .login-left-panel p.system-description {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #495057;
            margin-top: 5px;
        }

        .login-right-panel {
            width: 450px; /* Lebar tetap untuk form login */
            padding: 40px;
            background-image:linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('assetsmk/bacground2.jpg') }}');
            background-size: cover;
            background-repeat: repeat;
            background-position: center center;
            min-height: 50vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease-in-out;
         /* Smooth transition for width change */
        }


        .login-logo {
            margin-bottom: 30px; /* Jarak antara logo dan pesan */
            font-size: 2.2rem;
            font-weight: 700;
            color: #343a40;
            text-align: center;
            width: 100%; /* Pastikan logo mengambil lebar penuh */
        }

        .login-logo b {
            color: #007bff;
        }

        .login-box-msg {
            font-size: 1.1rem;
            color: #597d9cff;
            margin-bottom: 25px;
            text-align: center;
        }

        .login-card-body {
            padding: 0;
            width: 100%;
            color: #ffffff;
            border-radius:10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
            background-color: rgba(255, 255, 255, 0.7);
            padding-top:10px;
        }

        /* --- Cool Styles for Form Inputs --- */
        .input-group {
            margin-bottom: 20px;
            color: #ffffffff;
            width: 90%;
            margin-left:5%;
        }

        .input-group .form-control {
            border-radius: 8px; /* Sudut membulat pada input */
            padding: 0.75rem 1rem;
            height: auto;
            border: 1px solid #ced4da; /* Tambahkan border */
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            box-shadow: 0 0px 5px rgba(0, 0, 0, 0.5);
            background-color: rgba(255, 255, 255, 0.7);
        }

        .input-group .form-control:focus {
           border-color: #007bff; /* Border biru saat fokus */
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25); /* Glow biru saat fokus */
            outline: none; /* Hapus outline default browser */
        }

        .input-group-append .input-group-text {
            border-radius: 0 8px 8px 0; /* Sudut membulat hanya di kanan */
            background-color: #e9ecef;
            border-left: none;
            border: 1px solid #b1b1b1ff; /* Tambahkan border */
            padding: 0.75rem 1rem;
            color: #000000ff; /* Warna ikon */
            box-shadow: 0 0px 4px rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .btn-primary {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            background: linear-gradient(45deg, #007bff, #0056b3); /* Gradien biru yang menarik */
            border: none;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px); /* Lift button effect on hover */
            box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3); /* Stronger shadow */
            background: linear-gradient(45deg, #0056b3, #007bff); /* Reverse gradient */
        }

        .icheck-primary label {
            font-size: 0.95rem;
            color: #495057;
            cursor: pointer; /* Indicate that the label is clickable */
        }

        .icheck-primary input[type="checkbox"]:focus + label::before {
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25); /* Glow on checkbox when focused */
        }

       .mb-1 a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        .mb-1 a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .login-box {
                flex-direction: column;
                width: 100%; /* Take full width of its parent (body padding applied) */
                max-width: 400px; /* Slight reduction for better fit on narrower tablets/larger phones */
                margin: 20px auto;
                min-height: auto;
                border-radius: 15px; /* Ensure full box has rounded corners */
            }

            /* Hide the left panel on smaller screens */
            .login-left-panel {
                display: none; /* Hide the entire left panel */
                width: 0;
                padding: 0;
                flex: 0;
            }

            .login-right-panel {
                width: 100%; /* Take full width when left panel is hidden */
                padding: 30px; /* Adjust padding for mobile */
                order: 1; /* Make it the first/only visible panel */
                border-radius: 15px; /* Apply full border-radius since it's now the only visible panel */
                min-width: unset; /* Remove min-width restriction */
            }

            .login-logo {
                font-size: 1.8rem;
                margin-bottom: 20px;
            }

            .login-box-msg {
                font-size: 1rem;
                margin-bottom: 20px;
            }

            /* Ensure form elements and buttons scale correctly */
            .btn-block {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .login-box {
                width: calc(100% - 20px); /* Full width with 10px padding on each side from body */
                margin: 10px auto; /* Small margin from edges */
                border-radius: 10px; /* Slightly smaller border-radius for very small screens */
            }

            .login-right-panel {
                padding: 20px; /* Reduce padding further on very small screens */
            }

            .login-logo {
                font-size: 1.5rem; /* Smaller font for logo on very small screens */
                margin-bottom: 15px;
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
            <h2>Selamat Datang Teacher</h2>
            <p class="system-description">di Sistem Pembelajaran Siswa <span style="background-color:blue; padding:4px; color:white; font-size:16px;">{{ $schoolProfile->name }}</span>. Silakan masuk untuk mengakses fitur anda.</p>
        </div>
    </div>
    <div class="login-right-panel">
        <div class="login-logo">
            <a href="#"><b>Login</b> Siswa</a>
        </div>
        <div class="login-card-body">
            <p class="login-box-msg">Masuk sebagai Siswa</p>

            <form action="{{ route('siswa.login.post') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" required autofocus>
                    {{-- Alternatively, use NIS for login:
                    <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror" placeholder="NIS" value="{{ old('nis') }}" required autofocus>
                    --}}
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
                    {{-- @error('nis')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror --}}
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
                <div class="row"  style="margin:0px 10px 10px 10px;">
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

            <p class="mb-1" style="margin-left:20px; margin-bottom:10px;">
                <a href="#" id="forgotPasswordLink">Saya lupa password</a>
            </p>
        </div>
    </div>
</div>
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const forgotPasswordLink = document.getElementById('forgotPasswordLink');

        if (forgotPasswordLink) {
            forgotPasswordLink.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior

                // Display SweetAlert2 message
                Swal.fire({
                    icon: 'warning', // Use a warning icon for this type of message
                    title: 'Tidak Bisa Reset Password',
                    text: 'Anda tidak bisa mereset password Anda. Silakan hubungi admin sekolah untuk bantuan.',
                    confirmButtonText: 'Oke',
                    customClass: {
                        popup: 'my-custom-swal-popup',
                        title: 'my-custom-swal-title',
                        content: 'my-custom-swal-content',
                    }
                });
            });
        }
    });
</script>
</body>
</html>
