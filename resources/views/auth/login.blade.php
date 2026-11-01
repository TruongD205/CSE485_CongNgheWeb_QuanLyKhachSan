@extends('layouts.master')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-5">

        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><b>Đăng nhập</b></h5>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label"><b>Tên đăng nhập</b></label>
                        <input type="text" name="Username" class="form-control @error('Username') is-invalid @enderror" value="{{ old('Username') }}" placeholder="Nhập tên đăng nhập" />
                        @error('Username')
                        <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><b>Mật khẩu</b></label>
                        <input type="password" name="Password" class="form-control @error('Password') is-invalid @enderror" placeholder="Nhập mật khẩu" />
                        @error('Password')
                        <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="d-grid mb-3">
                        <input type="submit" class="btn btn-primary" value="Đăng nhập" />
                    </div>
                    <div class="text-center">
                        <span class="text-muted">Chưa có tài khoản?</span>
                        <a href="{{ route('register') }}" class="text-decoration-none">Đăng ký ngay</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
