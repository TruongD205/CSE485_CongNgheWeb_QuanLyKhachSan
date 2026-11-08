@extends('layouts.master')

@section('content')

<div class="card">
    <div class="card-header"><b>Xoá đặt phòng</b></div>
    <div class="card-body">

        @if ($message = Session::get('error'))
        <div class="alert alert-danger">{{ $message }}</div>
        @endif

        @if (!empty($hasInvoice))
        <div class="alert alert-danger">
            <b>ĐƠN ĐẶT PHÒNG ĐÃ XUẤT HÓA ĐƠN - KHÔNG THỂ XOÁ!</b><br>
            Đơn này đã phát sinh hóa đơn thanh toán (Mã HĐ: <b>{{ $booking->invoice->InvoiceCode }}</b> - Số tiền: <b>{{ number_format($booking->invoice->TotalAmount, 0, ',', '.') }} Đ</b>).<br>
            Theo quy định quản lý tài chính và kế toán, các đơn đã có hóa đơn không được phép xoá để bảo toàn doanh thu.
        </div>
        @endif

        <form method="post" action="{{ route('bookings.destroy', $booking->BookingID) }}">
            @csrf
            @method('DELETE')
            <div class="row mb-3">
                <label for="RoomID" class="col-sm-2 col-label-form">Phòng</label>
                <div class="col-sm-10">
                    <select id="RoomID" class="form-select" name="RoomID" disabled>
                        <option value="{{ $booking->room->RoomID }}">
                            Phòng {{ $booking->room->RoomNumber }} - {{ number_format($booking->room->RoomPrice, 0, ',', '.') }} Đ/Đêm
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
                    <input type="text" name="CheckInDate" class="form-control" value="{{ \Carbon\Carbon::parse($booking->CheckInDate)->format('d/m/Y') }}" readonly />
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Ngày trả phòng</label>
                <div class="col-sm-10">
                    <input type="text" name="CheckOutDate" class="form-control" value="{{ $booking->CheckOutDate ? \Carbon\Carbon::parse($booking->CheckOutDate)->format('d/m/Y') : 'Còn đang ở' }}" readonly />
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
            <div class="text-center mt-4">
                <a href="{{ route('bookings.index') }}" class="btn btn-secondary me-2">Quay lại</a>
                <input type="submit" class="btn btn-danger" value="Xoá" @if (!empty($hasInvoice)) disabled @endif />
            </div>
        </form>
    </div>
</div>

@endsection
