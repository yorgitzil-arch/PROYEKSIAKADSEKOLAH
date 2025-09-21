<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $schoolProfile->name ?? 'nama sekolah'}} | Login Guru</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
        }

 
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
            width: 900px; 
            max-width: 95%;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3); 
            border-radius: 15px;
            overflow: hidden;
            display: flex; 
            background-color: rgba(255, 255, 255, 0.9); 
            min-height: 500px;
        }

        .login-left-panel {
            flex: 1; 
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;  
            color: #343a40; 
            text-align: center; 
            position: relative;
            background: linear-gradient(to bottom right, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7)); 
            transition: all 0.3s ease-in-out; 
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
            width: 100%;
            padding-bottom: 0;
        }

        .login-left-panel h2 {
            font-size: 2.5rem; 
            font-weight: 700;
            margin-bottom: 10px;
            color: #007bff; 
        }

        .login-left-panel p.system-description {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #495057;
            margin-top: 5px;
        }

        .login-right-panel {
            width: 450px; 
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

        }

        .login-logo {
            margin-bottom: 30px; 
            font-size: 2.2rem;
            font-weight: 700;
            color: #343a40;
            text-align: center;
            width: 100%; 
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
        .input-group {
            margin-bottom: 20px;
            color: #ffffffff;
            width: 90%;
            margin-left:5%;
         
        }

        .input-group .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            height: auto;
            border: 1px solid #ced4da; 
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            box-shadow: 0 0px 5px rgba(0, 0, 0, 0.5);
            background-color: rgba(255, 255, 255, 0.7);
        }

        .input-group .form-control:focus {
            border-color: #007bff; 
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
            outline: none;
        }

        .input-group-append .input-group-text {
            border-radius: 0 8px 8px 0;
            background-color: #e9ecef;
            border-left: none;
            border: 1px solid #b1b1b1ff;
            padding: 0.75rem 1rem;
            color: #000000ff;
            box-shadow: 0 0px 4px rgba(0, 0, 0, 0.5);
            z-index: 1;
            
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
            color: #495057;
            cursor: pointer;

        }

        .icheck-primary input[type="checkbox"]:focus + label::before {
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
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


        @media (max-width: 992px) {
            .login-box {
                flex-direction: column;
                width: 100%; 
                max-width: 400px; 
                margin: 20px auto;
                min-height: auto;
                border-radius: 15px; 
            }

          
            .login-left-panel {
                display: none; 
                width: 0;
                padding: 0;
                flex: 0;
            }

            .login-right-panel {
                width: 100%;
                padding: 30px; 
                order: 1; 
                border-radius: 15px; 
                min-width: unset; 
            }

            .login-logo {
                font-size: 1.8rem;
                margin-bottom: 20px;
            }

            .login-box-msg {
                font-size: 1rem;
                margin-bottom: 20px;
                
            }

           
            .btn-block {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .login-box {
                width: calc(100% - 20px); 
                margin: 10px auto; 
                border-radius: 10px; 
            }

            .login-right-panel {
                padding: 20px;
            }

            .login-logo {
                font-size: 1.5rem;
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
            <p class="system-description">di Sistem Pembelajaran Guru <span style="background-color:blue; padding:4px; color:white; font-size:16px;">{{ $schoolProfile->name }}</span>. Silakan masuk untuk mengakses fitur-fitur guru.</p>
        </div>
    </div>
    <div class="login-right-panel">
        <div class="login-logo">
            <a href="#"><b>Login</b> Guru</a>
        </div>
        <div class="login-card-body">
            <p class="login-box-msg">Silahkan Masuk Sesuai dengan Akun Bapak/ibu sekalian</p>

            <form action="{{ route('guru.login.post') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" placeholder="Input Id Guru" value="{{ old('nip') }}" autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-id-card"></span>
                        </div>
                    </div>
                    @error('nip')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
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
                <div class="row " style="margin:0px 10px 10px 10px;">
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
                event.preventDefault(); 

            
                Swal.fire({
                    icon: 'info',
                    title: 'Lupa Password?',
                    text: 'Bapak/ibu, silakan menghubungi operator admin untuk mereset password',
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
