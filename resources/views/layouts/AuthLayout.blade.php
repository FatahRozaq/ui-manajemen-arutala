<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arutala | Register</title>
    <link href="{{ asset('assets/css/auth.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo/ArutalaPutih.png') }}">
</head>
<body>
    <div class="container">
        <div class="company-side">
            <div class="logo">
                <img src="{{ asset('assets/img/logo/ArutalaPutih.png') }}" alt="">
            </div>
        </div>

        <div class="form-side">
            @yield('form-content')
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function (e) {
        const passwordField = document.getElementById('password');
        const icon = this;
        
        // Toggle the type attribute of the password field
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        
        // Toggle the eye icon
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });

    </script>
</body>
</html>