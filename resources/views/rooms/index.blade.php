@extends('layouts.master')

@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">{{ $message }}</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger">{{ $message }}</div>
@endif

<div class="container mt-5">
    <h1 class="text-primary mt-3 mb-4 text-center"><b>Quản lý khách sạn</b></h1>
</div>

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col">
                <a href="{{ route('rooms.create') }}" class="btn btn-success btn-sm float-end">Thêm phòng mới</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Mã phòng</th>
                <th>Số phòng</th>
                <th>Loại phòng</th>
                <th>Giá phòng</th>
                <th>Sức chứa</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
            @if (count($rooms) > 0)

            @foreach ($rooms as $row)

            <tr>
                <td>{{ $row->RoomID }}</td>
                <td>{{ $row->RoomNumber }}</td>
                <td> @if ($row->RoomType == 'don') Phòng đơn @elseif ($row->RoomType == 'doi') Phòng đôi @else Phòng VIP @endif </td>
                <td>{{ number_format($row->RoomPrice, 0, ',', '.') }} Đ</td>
                <td>{{ $row->RoomCapacity }} Người</td>
                <td> @if ($row->RoomStatus == 0) Trống @elseif ($row->RoomStatus == 1) Đã đặt @else Đang ở @endif </td>
                <td>
                    <a href="{{ route('rooms.show', $row->RoomID) }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                    <a href="{{ route('rooms.edit', $row->RoomID) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <!-- đây là khi người dùng click nút sửa -->
                    <a href="{{ route('rooms.delete', $row->RoomID) }}" class="btn btn-danger btn-sm" @if ($row->RoomStatus == 2) onclick="return confirm('⚠️ Chú ý: Phòng {{ $row->RoomNumber }} hiện đang có người ở! Bạn có chắc muốn tiếp tục vào trang xác nhận xoá?');" @endif>Xoá</a>
                </td>
            </tr>

            @endforeach

            @else
            <tr>
                <td colspan="7" class="text-center">Chưa có dữ liệu</td>
            </tr>
            @endif
        </table>
        {!! $rooms->links() !!}
    </div>
</div>

@endsection