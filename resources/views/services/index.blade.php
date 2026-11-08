@extends('layouts.master')

@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
    {{ $message }}
</div>
@endif

<div class="container mt-5">
    <h1 class="text-primary mt-3 mb-4 text-center"><b>Quản lý dịch vụ</b></h1>
</div>

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col">
                <a href="{{ route('services.create') }}" class="btn btn-success btn-sm float-end">Thêm dịch vụ mới</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Mã DV</th>
                <th>Tên dịch vụ</th>
                <th>Đơn giá</th>
                <th>Đơn vị tính</th>
                <th>Thao tác</th>
            </tr>
            @if (count($services) > 0)
            @foreach ($services as $row)
            <tr>
                <td>{{ $row->ServiceID }}</td>
                <td>{{ $row->ServiceName }}</td>
                <td>{{ number_format($row->Price, 0, ',', '.') }} Đ</td>
                <td>{{ $row->Unit }}</td>
                <td>
                    <a href="{{ route('services.show', $row->ServiceID) }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                    <a href="{{ route('services.edit', $row->ServiceID) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form method="post" action="{{ route('services.destroy', $row->ServiceID) }}" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa dịch vụ này không?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="5" class="text-center">Chưa có dữ liệu</td>
            </tr>
            @endif
        </table>
        {!! $services->links() !!}
    </div>
</div>

@endsection
