@extends('layouts.master')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-5">

        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
        @endif

        @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            {{ $message }}
        </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><b>Đăng ký tài khoản</b></h5>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('register.submit') }}">
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
                        <input type="password" name="Password" class="form-control @error('Password') is-invalid @enderror" placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)" />
                        @error('Password')
                        <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><b>Xác nhận mật khẩu</b></label>
                        <input type="password" name="Password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><b>Gmail</b></label>
                        <input type="email" name="Gmail" class="form-control @error('Gmail') is-invalid @enderror" value="{{ old('Gmail') }}" placeholder="example@gmail.com" />
                        @error('Gmail')
                        <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="d-grid mb-3">
                        <input type="submit" class="btn btn-success" value="Đăng ký" />
                    </div>
                    <div class="text-center">
                        <span class="text-muted">Đã có tài khoản?</span>
                        <a href="{{ route('login') }}" class="text-decoration-none">Đăng nhập ngay</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
