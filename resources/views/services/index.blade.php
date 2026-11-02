@extends('layouts.master')

@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
    {{ $message }}
</div>
@endif

<div class="container mt-3">
    <h1 class="text-primary mb-4 text-center"><b>Quản lý Dịch vụ khách sạn</b></h1>
</div>

<div class="card shadow-sm">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col col-md-6">
                <b>Danh sách dịch vụ đang kinh doanh</b>
            </div>
            <div class="col col-md-6 text-end">
                <a href="{{ route('services.create') }}" class="btn btn-success btn-sm">Thêm dịch vụ mới</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Mã DV</th>
                    <th>Tên dịch vụ</th>
                    <th>Đơn giá</th>
                    <th>Đơn vị tính</th>
                    <th>Mô tả</th>
                    <th style="width: 160px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @if (count($services) > 0)
                @foreach ($services as $row)
                <tr>
                    <td>{{ $row->ServiceID }}</td>
                    <td><b>{{ $row->ServiceName }}</b></td>
                    <td class="text-danger"><b>{{ number_format($row->Price, 0, ',', '.') }} đ</b></td>
                    <td><span class="badge bg-secondary">{{ $row->Unit }}</span></td>
                    <td>{{ $row->Description ?: '-' }}</td>
                    <td>
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
                    <td colspan="6" class="text-center text-muted">Chưa có dịch vụ nào</td>
                </tr>
                @endif
            </tbody>
        </table>
        {!! $services->links() !!}
    </div>
</div>

@endsection

