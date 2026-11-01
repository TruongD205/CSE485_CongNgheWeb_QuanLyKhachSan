@extends('layouts.master')

@section('content')

<div class="container mt-5">
    <h1 class="text-danger mt-3 mb-4 text-center"><b>Xác nhận đăng xuất</b></h1>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <div class="row align-items-center">
                    <div class="col"><b>Bạn có chắc chắn muốn đăng xuất không?</b></div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <label class="col-sm-4 col-label-form"><b>Tài khoản hiện tại:</b></label>
                    <div class="col-sm-8">
                        <span class="badge bg-secondary fs-6">{{ Session::get('Username') }}</span>
                    </div>
                </div>
                <p class="text-muted">
                    Sau khi đăng xuất, bạn sẽ cần phải đăng nhập lại để tiếp tục xem và quản lý phòng cũng như đặt phòng.
                </p>

                <form method="post" action="{{ route('logout') }}" class="text-center mt-4">
                    @csrf
                    <a href="{{ route('rooms.index') }}" class="btn btn-secondary me-2">Không, quay lại</a>
                    <input type="submit" class="btn btn-danger" value="Xác nhận đăng xuất" />
                </form>
            </div>
        </div>
    </div>
</div>

@endsection