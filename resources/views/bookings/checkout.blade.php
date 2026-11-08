@extends('layouts.master')

@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <div class="card-header"><b>Thanh toán & Trả phòng</b></div>
    <div class="card-body">
        <form method="post" action="{{ route('bookings.checkout.process', $booking->BookingID) }}">
            @csrf

            <div class="row mb-3">
                <label class="col-sm-2 col-label-form"><b>Mã đặt phòng</b></label>
                <div class="col-sm-10">
                    {{ $booking->BookingID }}
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-label-form"><b>Khách hàng</b></label>
                <div class="col-sm-10">
                    {{ $booking->GuestName }} - SĐT: {{ $booking->GuestPhone }}
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-label-form"><b>Phòng lưu trú</b></label>
                <div class="col-sm-10">
                    Phòng {{ $booking->room->RoomNumber }} ({{ number_format($booking->room->RoomPrice, 0, ',', '.') }} Đ/Đêm)
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-label-form"><b>Ngày nhận phòng</b></label>
                <div class="col-sm-10">
                    {{ \Carbon\Carbon::parse($booking->CheckInDate)->format('d/m/Y') }}
                </div>
            </div>

            <div class="row mb-4">
                <label class="col-sm-2 col-label-form"><b>Ngày trả phòng thực tế</b></label>
                <div class="col-sm-4">
                    <input type="date" name="CheckOutDate" id="CheckOutDate" class="form-control" value="{{ old('CheckOutDate', $defaultCheckOutDate) }}" required />
                </div>
            </div>

            <div class="mb-4">
                <b>Bảng kê chi phí thanh toán</b>
            </div>

            <table class="table table-bordered mb-4">
                <tr>
                    <th>Mục thanh toán</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
                <tr>
                    <td>Tiền thuê phòng (Phòng {{ $booking->room->RoomNumber }})</td>
                    <td id="nights-display">{{ $nights }} Đêm</td>
                    <td>{{ number_format($booking->room->RoomPrice, 0, ',', '.') }} Đ</td>
                    <td id="room-amount-display">{{ number_format($roomAmount, 0, ',', '.') }} Đ</td>
                </tr>
                @if ($booking->services->count() > 0)
                @foreach ($booking->services as $svc)
                <tr>
                    <td>{{ $svc->ServiceName }}</td>
                    <td>{{ $svc->pivot->Quantity }} {{ $svc->Unit }}</td>
                    <td>{{ number_format($svc->pivot->Price, 0, ',', '.') }} Đ</td>
                    <td>{{ number_format($svc->pivot->TotalPrice, 0, ',', '.') }} Đ</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="4" class="text-center">Khách không sử dụng dịch vụ phụ trợ</td>
                </tr>
                @endif
                <tr>
                    <td colspan="3"><b>Tổng tiền phòng</b></td>
                    <td id="total-room-summary">{{ number_format($roomAmount, 0, ',', '.') }} Đ</td>
                </tr>
                <tr>
                    <td colspan="3"><b>Tổng tiền dịch vụ</b></td>
                    <td>{{ number_format($serviceAmount, 0, ',', '.') }} Đ</td>
                </tr>
                <tr>
                    <td colspan="3"><b>TỔNG CỘNG THANH TOÁN</b></td>
                    <td id="total-amount-display"><b>{{ number_format($totalAmount, 0, ',', '.') }} Đ</b></td>
                </tr>
            </table>

            <div class="row mb-3">
                <label class="col-sm-2 col-label-form"><b>Hình thức thanh toán</b></label>
                <div class="col-sm-4">
                    <select name="PaymentMethod" class="form-control" required>
                        <option value="tien_mat" {{ old('PaymentMethod') == 'tien_mat' ? 'selected' : '' }}>Tiền mặt</option>
                        <option value="chuyen_khoan" {{ old('PaymentMethod') == 'chuyen_khoan' ? 'selected' : '' }}>Chuyển khoản</option>
                        <option value="the" {{ old('PaymentMethod') == 'the' ? 'selected' : '' }}>Thẻ</option>
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <label class="col-sm-2 col-label-form"><b>Trạng thái thanh toán</b></label>
                <div class="col-sm-4">
                    <select name="PaymentStatus" class="form-control" required>
                        <option value="1" {{ old('PaymentStatus', '1') == '1' ? 'selected' : '' }}>Đã thanh toán</option>
                        <option value="0" {{ old('PaymentStatus') == '0' ? 'selected' : '' }}>Chưa thanh toán (Ghi nợ)</option>
                    </select>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('bookings.show', $booking->BookingID) }}" class="btn btn-secondary me-2">Quay lại</a>
                <button type="submit" class="btn btn-success" onclick="return confirm('Xác nhận thanh toán và hoàn tất trả phòng?');">Xác nhận thanh toán & Trả phòng</button>
            </div>
        </form>
    </div>
</div>

<script>
    const checkInDateStr = "{{ \Carbon\Carbon::parse($booking->CheckInDate)->format('Y-m-d') }}";
    const roomPrice = Number("{{ $booking->room->RoomPrice }}");
    const serviceAmount = Number("{{ $serviceAmount }}");

    document.getElementById('CheckOutDate').addEventListener('change', function () {
        const checkIn = new Date(checkInDateStr);
        const checkOut = new Date(this.value);

        if (isNaN(checkOut.getTime())) return;

        const diffTime = checkOut - checkIn;
        let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        if (diffDays < 1) diffDays = 1;

        const roomAmount = diffDays * roomPrice;
        const total = roomAmount + serviceAmount;

        const formatMoney = (num) => new Intl.NumberFormat('vi-VN').format(num) + ' Đ';

        document.getElementById('nights-display').innerText = diffDays + ' Đêm';
        document.getElementById('room-amount-display').innerText = formatMoney(roomAmount);
        document.getElementById('total-room-summary').innerText = formatMoney(roomAmount);
        document.getElementById('total-amount-display').innerHTML = '<b>' + formatMoney(total) + '</b>';
    });
</script>

@endsection

