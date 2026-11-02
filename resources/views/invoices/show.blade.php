@extends('layouts.master')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow border-success">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><b>HÓA ĐƠN THANH TOÁN ({{ $invoice->InvoiceCode }})</b></h5>
                <span class="badge bg-light text-success fs-6">Đã thanh toán</span>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4 border-bottom pb-3">
                    <div class="col-6">
                        <h4 class="text-primary mb-1"><b>Khách Sạn Grand Hotel</b></h4>
                        <p class="text-muted mb-0">Địa chỉ: 123 Đường Biển, TP. Đà Nẵng</p>
                        <p class="text-muted">Hotline: 1900 6868</p>
                    </div>
                    <div class="col-6 text-end">
                        <p class="mb-1"><b>Mã hóa đơn:</b> {{ $invoice->InvoiceCode }}</p>
                        <p class="mb-1"><b>Ngày xuất:</b> {{ $invoice->PaymentDate->format('d/m/Y H:i:s') }}</p>
                        <p class="mb-0"><b>Hình thức:</b> 
                            @if ($invoice->PaymentMethod == 'tien_mat') Tiền mặt @elseif ($invoice->PaymentMethod == 'chuyen_khoan') Chuyển khoản @else Thẻ @endif
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-muted">THÔNG TIN KHÁCH HÀNG:</h6>
                    <p class="mb-1"><b>Họ tên khách:</b> {{ $invoice->booking ? $invoice->booking->GuestName : 'Khách vãng lai' }}</p>
                    <p class="mb-1"><b>Số điện thoại:</b> {{ $invoice->booking ? $invoice->booking->GuestPhone : '-' }}</p>
                    <p class="mb-0"><b>Phòng lưu trú:</b> {{ $invoice->booking && $invoice->booking->room ? 'Phòng ' . $invoice->booking->room->RoomNumber . ' (' . number_format($invoice->booking->room->RoomPrice, 0, ',', '.') . ' đ/đêm)' : '-' }}</p>
                </div>

                <table class="table table-bordered mb-4">
                    <thead class="table-light">
                        <tr>
                            <th>Mục thanh toán</th>
                            <th class="text-end" style="width: 200px;">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tiền thuê phòng ({{ $invoice->booking ? $invoice->booking->nights() : 1 }} đêm)</td>
                            <td class="text-end">{{ number_format($invoice->RoomAmount, 0, ',', '.') }} đ</td>
                        </tr>
                        <tr>
                            <td>Tiền dịch vụ phát sinh</td>
                            <td class="text-end">{{ number_format($invoice->ServiceAmount, 0, ',', '.') }} đ</td>
                        </tr>
                        <tr class="table-warning">
                            <td><b>TỔNG CỘNG THANH TOÁN</b></td>
                            <td class="text-end text-danger fs-5"><b>{{ number_format($invoice->TotalAmount, 0, ',', '.') }} đ</b></td>
                        </tr>
                    </tbody>
                </table>

                <div class="text-center mt-4">
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary me-2">Quay lại danh sách</a>
                    <button onclick="window.print()" class="btn btn-primary">In hóa đơn</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

