@extends('layouts/AuthLayout')

@section('form-content')
<div class="form-title">
    Masuk
</div>

<form action="" class="form-daftar">
    <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
        <label for="email" class="form-label">Email</label>
    </div>

    <div class="form-group">
        <div class="input-group">
            <input type="password" name="password" id="password" class="form-control" placeholder="...." required>
            <span class="input-group-append">
                <i class="fa fa-eye-slash toggle-password" id="togglePassword"></i>
            </span>
        </div>
        <label for="password" class="form-label">Password</label>
    </div>

    <button>
        Login
    </button>
</form>

<div class="separator">
    <span>Atau</span>
</div>

<a href="{{ route('register.page') }}">
    <button class="daftar">
        Sign Up
    </button>
</a>
@endsection