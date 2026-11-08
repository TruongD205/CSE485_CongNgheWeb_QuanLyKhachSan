@extends('layouts.master')

@section('content')

<div class="card">
    <div class="card-header"><b>Chi tiết hóa đơn thanh toán</b></div>
    <div class="card-body">
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Mã hóa đơn</b></label>
            <div class="col-sm-10">
                {{ $invoice->InvoiceCode }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Khách hàng</b></label>
            <div class="col-sm-10">
                {{ $invoice->booking ? $invoice->booking->GuestName : 'Khách vãng lai' }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Điện thoại</b></label>
            <div class="col-sm-10">
                {{ $invoice->booking ? $invoice->booking->GuestPhone : '-' }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Phòng</b></label>
            <div class="col-sm-10">
                {{ $invoice->booking && $invoice->booking->room ? 'Phòng ' . $invoice->booking->room->RoomNumber : '-' }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Ngày thanh toán</b></label>
            <div class="col-sm-10">
                {{ $invoice->PaymentDate ? \Carbon\Carbon::parse($invoice->PaymentDate)->format('d/m/Y H:i:s') : '-' }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Hình thức</b></label>
            <div class="col-sm-10">
                @if ($invoice->PaymentMethod == 'tien_mat') Tiền mặt @elseif ($invoice->PaymentMethod == 'chuyen_khoan') Chuyển khoản @else Thẻ @endif
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Trạng thái</b></label>
            <div class="col-sm-10">
                @if ($invoice->PaymentStatus == '1') Đã thanh toán @else Chưa thanh toán @endif
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Tiền phòng</b></label>
            <div class="col-sm-10">
                {{ number_format($invoice->RoomAmount, 0, ',', '.') }} Đ
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Tiền dịch vụ</b></label>
            <div class="col-sm-10">
                {{ number_format($invoice->ServiceAmount, 0, ',', '.') }} Đ
            </div>
        </div>
        <div class="row mb-4">
            <label class="col-sm-2 col-label-form"><b>Tổng tiền</b></label>
            <div class="col-sm-10">
                {{ number_format($invoice->TotalAmount, 0, ',', '.') }} Đ
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary me-2">Quay lại</a>
            @if ($invoice->PaymentStatus == '0')
            <form method="post" action="{{ route('invoices.pay', $invoice->InvoiceID) }}" class="d-inline" onsubmit="return confirm('Xác nhận hóa đơn này đã được thanh toán?');">
                @csrf
                <button type="submit" class="btn btn-success">Xác nhận thu tiền</button>
            </form>
            @endif
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <b>Bảng kê chi tiết thanh toán</b>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Mục thanh toán</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
            @php
                $nights = max(1, $invoice->booking ? $invoice->booking->nights() : 1);
                $roomUnitPrice = $invoice->RoomAmount > 0 ? (int)($invoice->RoomAmount / $nights) : ($invoice->booking && $invoice->booking->room ? $invoice->booking->room->RoomPrice : 0);
            @endphp
            <tr>
                <td>Tiền thuê phòng ({{ $invoice->booking && $invoice->booking->room ? 'Phòng ' . $invoice->booking->room->RoomNumber : '-' }})</td>
                <td>{{ $nights }} Đêm</td>
                <td>{{ number_format($roomUnitPrice, 0, ',', '.') }} Đ</td>
                <td>{{ number_format($invoice->RoomAmount, 0, ',', '.') }} Đ</td>
            </tr>
            @if ($invoice->booking && $invoice->booking->services->count() > 0)
            @foreach ($invoice->booking->services as $svc)
            <tr>
                <td>{{ $svc->ServiceName }}</td>
                <td>{{ $svc->pivot->Quantity }} {{ $svc->Unit }}</td>
                <td>{{ number_format($svc->pivot->Price, 0, ',', '.') }} Đ</td>
                <td>{{ number_format($svc->pivot->TotalPrice, 0, ',', '.') }} Đ</td>
            </tr>
            @endforeach
            @endif
            <tr>
                <td colspan="3"><b>Tổng cộng thanh toán</b></td>
                <td>{{ number_format($invoice->TotalAmount, 0, ',', '.') }} Đ</td>
            </tr>
        </table>
    </div>
</div>

@endsection
