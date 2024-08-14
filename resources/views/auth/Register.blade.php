@extends('layouts/AuthLayout')

@section('form-content')
<div class="form-title">
    Daftar Akun
</div>

<form action="" class="form-daftar">
    <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
        <label for="email" class="form-label">Email</label>
    </div>

    <div class="form-group">
        <div class="no-kontak">
            <div class="default">
                <label>+62</label>
            </div>
            <input type="number" name="no_kontak" id="noKontak" class="form-control" placeholder="81121211" required>
        </div>
        
        <label for="no_kontak" class="form-label kontak">No Kontak</label>
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
        Submit
    </button>
</form>
@endsection