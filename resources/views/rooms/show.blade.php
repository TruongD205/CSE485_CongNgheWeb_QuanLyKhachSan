@extends('layouts.master')

@section('content')

@if ($message = Session::get('success'))

<div class="alert alert-success">
    {{ $message }}
</div>

@endif

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col col-md-6"><b>Thông tin phòng chi tiết</b></div>
            <div class="col col-md-6">
                <a href="{{ route('rooms.index') }}" class="btn btn-primary btn-sm float-end">Xem tất cả danh sách</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Mã phòng</b></label>
            <div class="col-sm-10">
                {{ $room->RoomID }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Số phòng</b></label>
            <div class="col-sm-10">
                {{ $room->RoomNumber }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Loại phòng</b></label>
            <div class="col-sm-10">
                @if ($room->RoomType == 'don')
                Phòng đơn
                @elseif ($room->RoomType == 'doi')
                Phòng đôi
                @else
                Phòng VIP
                @endif
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Giá phòng</b></label>
            <div class="col-sm-10">
                {{ number_format($room->RoomPrice, 0, ',', '.') }} đ/đêm
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Sức chứa</b></label>
            <div class="col-sm-10">
                {{ $room->RoomCapacity }} người
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Trạng thái</b></label>
            <div class="col-sm-10">
                @if ($room->RoomStatus == 0)
                Trống
                @elseif ($room->RoomStatus == 1)
                Đã đặt
                @else
                Đang ở
                @endif
            </div>
        </div>
        <div class="row mb-4">
            <label class="col-sm-2 col-label-form"><b>Mô tả</b></label>
            <div class="col-sm-10">
                {{ $room->RoomDescription }}
            </div>
        </div>
        <div class="text-center">
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <div class="row">
            <div class="col col-md-6"><b>Danh sách đặt phòng của phòng {{ $room->RoomNumber }}</b></div>
            <div class="col col-md-6">
                <a href="{{ route('bookings.create') }}" class="btn btn-success btn-sm float-end">Thêm đặt phòng</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Mã đặt phòng</th>
                <th>Tên khách</th>
                <th>Điện thoại</th>
                <th>Ngày nhận phòng</th>
                <th>Ngày trả phòng</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
            @if (count($room->bookings) > 0)

            @foreach ($room->bookings as $row)

            <tr>
                <td>{{ $row->BookingID }}</td>
                <td>{{ $row->GuestName }}</td>
                <td>{{ $row->GuestPhone }}</td>
                <td>{{ $row->CheckInDate->format('d/m/Y') }}</td>
                <td>{{ $row->CheckOutDate ? $row->CheckOutDate->format('d/m/Y') : 'Chưa trả phòng' }}</td>
                <td> @if ($row->BookingStatus == 0) Đã xác nhận @elseif ($row->BookingStatus == 1) Đang ở @else Đã trả phòng @endif </td>
                <td>
                    <a href="{{ route('bookings.show', $row->BookingID) }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                    <a href="{{ route('bookings.edit', $row->BookingID) }}" class="btn btn-warning btn-sm">Sửa</a>
                </td>
            </tr>

            @endforeach

            @else
            <tr>
                <td colspan="7" class="text-center">No Data Found</td>
            </tr>
            @endif
        </table>
    </div>
</div>

@endsection
