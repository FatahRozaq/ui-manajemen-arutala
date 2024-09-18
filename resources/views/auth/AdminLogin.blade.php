@extends('layouts.AuthLayout')

@section('title')
Arutala | Login Admin
@endsection

@section('form-content')
<div class="form-title">
    Masuk Halaman Admin
</div>

<form id="loginForm" class="form-daftar">
    <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="example@gmail.com">
        <label for="email" class="form-label">Email</label>
        <span class="text-danger" id="error-email"></span>
    </div>

    <div class="form-group">
        <div class="input-group">
            <input type="password" name="password" id="password" class="form-control" placeholder="....">
            <span class="input-group-append">
                <i class="fa fa-eye-slash toggle-password" id="togglePassword"></i>
            </span>
        </div>
        <label for="password" class="form-label">Password</label>
        <span class="text-danger" id="error-password"></span>
    </div>

    <button type="submit">
        Login
    </button>
</form>
@endsection

@section('scripts')
<script>
    const style = document.createElement('style');
    style.textContent = `
        .form-control.is-invalid {
            border-color: red;
        }
        .text-danger {
            color: red;
            font-size: 0.875rem;
        }
    `;
    document.head.appendChild(style);

    document.addEventListener('DOMContentLoaded', function () {
        const token = localStorage.getItem('auth_token');

        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        }

        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault();

            clearErrors();

            const email = document.querySelector('input[name="email"]').value;
            const password = document.querySelector('input[name="password"]').value;

            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/api/login-admin', {
                    email: email,
                    password: password
                })
                .then(response => {
                    localStorage.setItem('auth_token', response.data.token);
                    localStorage.setItem('auth_user', JSON.stringify(response.data.data));

                    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;

                    return axios.post('/save-session', {
                        user: {
                            ...response.data.data,
                            role: 'admin'
                        }
                    });
                })
                .then(() => {
                    Swal.fire({
                        title: 'Sukses!',
                        text: 'Berhasil login!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/admin/dashboard';
                        }
                    });
                })
                .catch(error => {
                    if (error.response && error.response.data && error.response.data.error) {
                        const errors = error.response.data.error;

                        if (errors.email && errors.email[0] === 'Email atau password salah') {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Email atau password salah',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            displayErrors(errors);
                        }
                    } else if (error.response && error.response.data && error.response.data.message) {
                        Swal.fire({
                            title: 'Error!',
                            text: error.response.data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat login.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });

        function displayErrors(errors) {
            for (let key in errors) {
                if (errors.hasOwnProperty(key)) {
                    const errorElement = document.getElementById(`error-${key}`);
                    const inputElement = document.querySelector(`input[name="${key}"]`);

                    if (errorElement && inputElement) {
                        errorElement.textContent = errors[key][0];
                        inputElement.classList.add('is-invalid');
                    }
                }
            }
        }

        function clearErrors() {
            const errorElements = document.querySelectorAll('.text-danger');
            const inputElements = document.querySelectorAll('.form-control');

            errorElements.forEach(element => element.textContent = '');
            inputElements.forEach(element => element.classList.remove('is-invalid'));
        }
    });
</script>
@endsection
