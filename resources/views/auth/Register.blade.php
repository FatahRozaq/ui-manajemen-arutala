@extends('layouts.AuthLayout')

@section('title')
Arutala | Register Peserta
@endsection

@section('form-content')
<div class="form-title">
    Daftar Akun
</div>

<form id="registerForm" class="form-daftar">
    <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="example@gmail.com">
        <label for="email" class="form-label">Email</label>
        <span class="text-danger" id="error-email"></span>
    </div>

    <div class="form-group">
        <div class="no-kontak">
            <div class="default">
                <label>+62</label>
            </div>
            <input type="number" name="no_kontak" id="noKontak" class="form-control" placeholder="8112121971">
        </div>
        <label for="no_kontak" class="form-label kontak">Kontak</label>
        <span class="text-danger" id="error-no_kontak"></span>
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
        Submit
    </button>
</form>
@endsection

@section('scripts')
<script>
    // Styling untuk input yang memiliki error dan teks error
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

    document.getElementById('registerForm').addEventListener('submit', function (e) {
        e.preventDefault();

        // Clear previous errors
        clearErrors();

        const email = document.querySelector('input[name="email"]').value;
        const no_kontak = document.querySelector('input[name="no_kontak"]').value;
        const password = document.querySelector('input[name="password"]').value;

        axios.post('/api/register', {
            email: email,
            no_kontak: no_kontak,
            password: password
        })
        .then(response => {
            Swal.fire({
                title: 'Sukses!',
                text: response.data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/login-page';
                }
            });
        })
        .catch(error => {
            if (error.response && error.response.data && error.response.data.error) {
                const errors = error.response.data.error;
                displayErrors(errors);
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat mendaftar.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
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
</script>
@endsection
