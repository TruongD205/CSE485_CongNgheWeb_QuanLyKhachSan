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
            <div class="col col-md-6"><b>Thông tin đặt phòng chi tiết</b></div>
            <div class="col col-md-6">
                <a href="{{ route('bookings.index') }}" class="btn btn-primary btn-sm float-end">Xem tất cả danh sách</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Mã đặt phòng</b></label>
            <div class="col-sm-10">
                {{ $booking->BookingID }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Phòng</b></label>
            <div class="col-sm-10">
                Phòng {{ $booking->room->RoomNumber }} -
                @if ($booking->room->RoomType == 'don')
                Phòng đơn
                @elseif ($booking->room->RoomType == 'doi')
                Phòng đôi
                @else
                Phòng VIP
                @endif
                - {{ number_format($booking->room->RoomPrice, 0, ',', '.') }} đ/đêm
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Tên khách</b></label>
            <div class="col-sm-10">
                {{ $booking->GuestName }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Điện thoại</b></label>
            <div class="col-sm-10">
                {{ $booking->GuestPhone }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Địa chỉ Email</b></label>
            <div class="col-sm-10">
                {{ $booking->GuestEmail }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Ngày nhận phòng</b></label>
            <div class="col-sm-10">
                {{ $booking->CheckInDate->format('d/m/Y') }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Ngày trả phòng</b></label>
            <div class="col-sm-10">
                {{ $booking->CheckOutDate ? $booking->CheckOutDate->format('d/m/Y') : 'Chưa trả phòng' }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Số đêm</b></label>
            <div class="col-sm-10">
                {{ $booking->nights() ? $booking->nights() . ' đêm' : 'Chưa xác định' }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Tạm tính</b></label>
            <div class="col-sm-10">
                @if ($nights = $booking->nights())
                {{ number_format($nights * $booking->room->RoomPrice, 0, ',', '.') }} đ
                @else
                -
                @endif
            </div>
        </div>
        <div class="row mb-4">
            <label class="col-sm-2 col-label-form"><b>Trạng thái</b></label>
            <div class="col-sm-10">
                @if ($booking->BookingStatus == 0)
                Đã xác nhận
                @elseif ($booking->BookingStatus == 1)
                Đang ở
                @else
                Đã trả phòng
                @endif
            </div>
        </div>
        <div class="text-center">
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>

@endsection
