@extends('layouts.master')

@section('content')

<div class="card">
    <div class="card-header">Xoá đặt phòng</div>
    <div class="card-body">
        <form method="post" action="{{ route('bookings.destroy', $booking->BookingID) }}">
            @csrf
            @method('DELETE')
            <div class="row mb-3">
                <label for="RoomID" class="col-sm-2 col-label-form">Phòng</label>
                <div class="col-sm-10">
                    <select id="RoomID" class="form-select" name="RoomID" disabled>
                        <option value="{{ $booking->room->RoomID }}">
                            Phòng {{ $booking->room->RoomNumber }} - {{ number_format($booking->room->RoomPrice, 0, ',', '.') }} đ/đêm
                        </option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Họ tên khách</label>
                <div class="col-sm-10">
                    <input type="text" name="GuestName" class="form-control" value="{{ $booking->GuestName }}" readonly />
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Điện thoại</label>
                <div class="col-sm-10">
                    <input type="text" name="GuestPhone" class="form-control" value="{{ $booking->GuestPhone }}" readonly />
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Địa chỉ Email</label>
                <div class="col-sm-10">
                    <input type="text" name="GuestEmail" class="form-control" value="{{ $booking->GuestEmail }}" readonly />
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Ngày nhận phòng</label>
                <div class="col-sm-10">
                    <input type="date" name="CheckInDate" class="form-control"
                        value="{{ $booking->CheckInDate ? $booking->CheckInDate->format('Y-m-d') : '' }}" readonly />
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Ngày trả phòng</label>
                <div class="col-sm-10">
                    <input type="date" name="CheckOutDate" class="form-control"
                        value="{{ $booking->CheckOutDate ? $booking->CheckOutDate->format('Y-m-d') : '' }}" readonly />
                </div>
            </div>
            <div class="row mb-4">
                <label class="col-sm-2 col-label-form">Trạng thái</label>
                <div class="col-sm-10">
                    <select name="BookingStatus" class="form-control" disabled>
                        <option value="0" {{ $booking->BookingStatus == '0' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="1" {{ $booking->BookingStatus == '1' ? 'selected' : '' }}>Đang ở</option>
                        <option value="2" {{ $booking->BookingStatus == '2' ? 'selected' : '' }}>Đã trả phòng</option>
                    </select>
                </div>
            </div>
            <div class="text-center">
                <a href="{{ route('bookings.index') }}" class="btn btn-secondary me-2">Quay lại</a>
                <input type="submit" class="btn btn-danger" value="Xoá" />
            </div>
        </form>
    </div>
</div>

@endsection
