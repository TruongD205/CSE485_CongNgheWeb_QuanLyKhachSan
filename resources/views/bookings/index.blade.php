@extends('layouts.master')

@section('content')

@if ($message = Session::get('success'))

<div class="alert alert-success">
    {{ $message }}
</div>

@endif

<div class="container mt-5">
    <h1 class="text-primary mt-3 mb-4 text-center"><b>Quản lý đặt phòng</b></h1>
</div>

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col col-md-6"><b><a href="{{ route('rooms.index') }}">Xem danh sách phòng</a></b></div>
            <div class="col col-md-6">
                <a href="{{ route('bookings.create') }}" class="btn btn-success btn-sm float-end">Thêm đặt phòng mới</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Mã đặt phòng</th>
                <th>Phòng</th>
                <th>Tên khách</th>
                <th>Điện thoại</th>
                <th>Ngày nhận phòng</th>
                <th>Ngày trả phòng</th>
                <th>Tạm tính</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
            @if (count($bookings) > 0)

            @foreach ($bookings as $row)

            <tr>
                <td>{{ $row->BookingID }}</td>
                <td>{{ $row->room->RoomNumber }}</td>
                <td>{{ $row->GuestName }}</td>
                <td>{{ $row->GuestPhone }}</td>
                <td>{{ $row->CheckInDate->format('d/m/Y') }}</td>
                <td>{{ $row->CheckOutDate ? $row->CheckOutDate->format('d/m/Y') : 'Còn đang ở' }}</td>
                <td>
                    @if ($nights = $row->nights())
                    {{ number_format($nights * $row->room->RoomPrice, 0, ',', '.') }} đ
                    @else
                    -
                    @endif
                </td>
                <td> @if ($row->BookingStatus == 0) Đã xác nhận @elseif ($row->BookingStatus == 1) Đang ở @else Đã trả phòng @endif </td>
                <td>
                    <a href="{{ route('bookings.show', $row->BookingID) }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                    <a href="{{ route('bookings.edit', $row->BookingID) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <a href="{{ route('bookings.delete', $row->BookingID) }}" class="btn btn-danger btn-sm">Xoá</a>
                </td>
            </tr>

            @endforeach

            @else
            <tr>
                <td colspan="9" class="text-center">No Data Found</td>
            </tr>
            @endif
        </table>
        {!! $bookings->links() !!}
    </div>
</div>

@endsection
