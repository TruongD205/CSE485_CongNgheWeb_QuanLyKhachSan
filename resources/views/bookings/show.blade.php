@extends('layouts.master')

@section('content')

@if ($message = Session::get('success'))

<div class="alert alert-success">
    {{ $message }}
</div>

@endif

<div class="card">
    <div class="card-header"><b>Thông tin đặt phòng chi tiết</b></div>
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
                - {{ number_format($booking->room->RoomPrice, 0, ',', '.') }} Đ/Đêm
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
                {{ \Carbon\Carbon::parse($booking->CheckInDate)->format('d/m/Y') }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Ngày trả phòng</b></label>
            <div class="col-sm-10">
                {{ $booking->CheckOutDate ? \Carbon\Carbon::parse($booking->CheckOutDate)->format('d/m/Y') : 'Chưa trả phòng' }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Số đêm</b></label>
            <div class="col-sm-10">
                {{ $booking->nights() ? $booking->nights() . ' Đêm' : 'Chưa xác định' }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Tạm tính</b></label>
            <div class="col-sm-10">
                @if ($nights = $booking->nights())
                {{ number_format($nights * $booking->room->RoomPrice, 0, ',', '.') }} Đ
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
        <div class="text-center mt-4">
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary me-2">Quay lại</a>
            @if ($booking->BookingStatus != 2)
            <a href="{{ route('bookings.checkout', $booking->BookingID) }}" class="btn btn-success">Thanh toán & Trả phòng</a>
            @endif
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <div class="row">
            <div class="col col-md-6"><b>Dịch vụ khách đã sử dụng</b></div>
            <div class="col col-md-6 text-end">
                Tổng tiền dịch vụ: {{ number_format($booking->services->sum('pivot.TotalPrice'), 0, ',', '.') }} Đ
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Tên dịch vụ</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                @if ($booking->BookingStatus != 2)
                <th>Thao tác</th>
                @endif
            </tr>
            @if ($booking->services->count() > 0)
            @foreach ($booking->services as $svc)
            <tr>
                <td>{{ $svc->ServiceName }}</td>
                <td>{{ number_format($svc->pivot->Price, 0, ',', '.') }} Đ / {{ $svc->Unit }}</td>
                <td>{{ $svc->pivot->Quantity }} {{ $svc->Unit }}</td>
                <td>{{ number_format($svc->pivot->TotalPrice, 0, ',', '.') }} Đ</td>
                @if ($booking->BookingStatus != 2)
                <td>
                    <form method="post" action="{{ route('bookings.services.remove', [$booking->BookingID, $svc->pivot->BookingServiceID]) }}" onsubmit="return confirm('Bạn có chắc chắn muốn hủy dịch vụ này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
                @endif
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="{{ $booking->BookingStatus != 2 ? 5 : 4 }}" class="text-center">Chưa có dịch vụ nào</td>
            </tr>
            @endif
        </table>

        @if ($booking->BookingStatus != 2 && isset($availableServices) && $availableServices->count() > 0)
        <hr />
        <h6 class="text-primary mb-3"><b>Thêm dịch vụ cho khách:</b></h6>
        <form method="post" action="{{ route('bookings.services.add', $booking->BookingID) }}" class="row g-3 align-items-end">
            @csrf
            <div class="col-md-6">
                <label class="form-label">Chọn dịch vụ</label>
                <select name="ServiceID" class="form-select" required>
                    <option value="">-- Chọn dịch vụ --</option>
                    @foreach ($availableServices as $asvc)
                    <option value="{{ $asvc->ServiceID }}">{{ $asvc->ServiceName }} ({{ number_format($asvc->Price, 0, ',', '.') }} Đ / {{ $asvc->Unit }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Số lượng</label>
                <input type="number" name="Quantity" class="form-control" value="1" min="1" max="99" required />
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success w-100">Ghi nhận dịch vụ</button>
            </div>
        </form>
        @endif
    </div>
</div>

@if ($booking->invoice)
<div class="card mt-4">
    <div class="card-header">
        <b>Thông tin hóa đơn thanh toán</b>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <label class="col-sm-3 col-label-form"><b>Mã hóa đơn:</b></label>
            <div class="col-sm-9">
                <a href="{{ route('invoices.show', $booking->invoice->InvoiceID) }}">{{ $booking->invoice->InvoiceCode }}</a>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 col-label-form"><b>Tiền phòng:</b></label>
            <div class="col-sm-9">
                {{ number_format($booking->invoice->RoomAmount, 0, ',', '.') }} Đ
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 col-label-form"><b>Tiền dịch vụ:</b></label>
            <div class="col-sm-9">
                {{ number_format($booking->invoice->ServiceAmount, 0, ',', '.') }} Đ
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 col-label-form"><b>Tổng tiền thanh toán:</b></label>
            <div class="col-sm-9">
                {{ number_format($booking->invoice->TotalAmount, 0, ',', '.') }} Đ
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 col-label-form"><b>Hình thức thanh toán:</b></label>
            <div class="col-sm-9">
                @if ($booking->invoice->PaymentMethod == 'tien_mat')
                Tiền mặt
                @elseif ($booking->invoice->PaymentMethod == 'chuyen_khoan')
                Chuyển khoản
                @else
                Thẻ
                @endif
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 col-label-form"><b>Trạng thái:</b></label>
            <div class="col-sm-9">
                @if ($booking->invoice->PaymentStatus == '1')
                Đã thanh toán
                @else
                Chưa thanh toán
                @endif
            </div>
        </div>
        <div class="row mb-2">
            <label class="col-sm-3 col-label-form"><b>Thời điểm thanh toán:</b></label>
            <div class="col-sm-9">
                {{ \Carbon\Carbon::parse($booking->invoice->PaymentDate)->format('d/m/Y H:i:s') }}
            </div>
        </div>
    </div>
</div>
@endif

@endsection
