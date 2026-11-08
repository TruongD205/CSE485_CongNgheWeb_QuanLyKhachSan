@extends('layouts.master')

@section('content')

<div class="card">
    <div class="card-header"><b>Sửa thông tin đặt phòng</b></div>
    <div class="card-body">
        <form method="post" action="{{ route('bookings.update', $booking->BookingID) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row mb-3">
                <label for="RoomID" class="col-sm-2 col-label-form">Phòng</label>
                <div class="col-sm-10">
                    <select id="RoomID" class="form-select @error('RoomID') is-invalid @enderror" name="RoomID" required>
                        @foreach ($rooms as $room)
                        <option value="{{ $room->RoomID }}" @if (old('RoomID', $booking->RoomID) == $room->RoomID) selected @endif>
                            Phòng {{ $room->RoomNumber }} - {{ number_format($room->RoomPrice, 0, ',', '.') }} Đ/Đêm
                        </option>
                        @endforeach
                    </select>
                    @error('RoomID')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Họ tên khách</label>
                <div class="col-sm-10">
                    <input type="text" name="GuestName" class="form-control @error('GuestName') is-invalid @enderror" value="{{ old('GuestName', $booking->GuestName) }}" />
                    @error('GuestName')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Điện thoại</label>
                <div class="col-sm-10">
                    <input type="text" name="GuestPhone" class="form-control @error('GuestPhone') is-invalid @enderror" value="{{ old('GuestPhone', $booking->GuestPhone) }}" />
                    @error('GuestPhone')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Địa chỉ Email</label>
                <div class="col-sm-10">
                    <input type="text" name="GuestEmail" class="form-control @error('GuestEmail') is-invalid @enderror" value="{{ old('GuestEmail', $booking->GuestEmail) }}" />
                    @error('GuestEmail')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Ngày nhận phòng</label>
                <div class="col-sm-10">
                    <input type="date" name="CheckInDate" class="form-control @error('CheckInDate') is-invalid @enderror"
                        value="{{ old('CheckInDate', $booking->CheckInDate ? \Carbon\Carbon::parse($booking->CheckInDate)->format('Y-m-d') : '') }}" />
                    @error('CheckInDate')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Ngày trả phòng</label>
                <div class="col-sm-10">
                    <input type="date" name="CheckOutDate" class="form-control @error('CheckOutDate') is-invalid @enderror"
                        value="{{ old('CheckOutDate', $booking->CheckOutDate ? \Carbon\Carbon::parse($booking->CheckOutDate)->format('Y-m-d') : '') }}" />
                    <small class="text-muted">Bỏ trống nếu khách đang ở và chưa xác định ngày trả phòng.</small>
                    @error('CheckOutDate')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-4">
                <label class="col-sm-2 col-label-form">Trạng thái</label>
                <div class="col-sm-10">
                    <select name="BookingStatus" class="form-control @error('BookingStatus') is-invalid @enderror">
                        <option value="0" {{ old('BookingStatus', $booking->BookingStatus) == '0' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="1" {{ old('BookingStatus', $booking->BookingStatus) == '1' ? 'selected' : '' }}>Đang ở</option>
                        <option value="2" {{ old('BookingStatus', $booking->BookingStatus) == '2' ? 'selected' : '' }}>Đã trả phòng</option>
                    </select>
                    @error('BookingStatus')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('bookings.index') }}" class="btn btn-secondary me-2">Quay lại</a>
                <input type="submit" class="btn btn-warning" value="Lưu thay đổi" />
            </div>
        </form>
    </div>
</div>

@endsection
