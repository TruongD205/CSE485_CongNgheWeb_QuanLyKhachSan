@extends('layouts.master')

@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
    {{ $message }}
</div>
@endif

<div class="container mt-3">
    <h1 class="text-primary mb-4 text-center"><b>Quản lý Hóa đơn & Doanh thu</b></h1>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Tổng số hóa đơn đã xuất</h6>
                <h3 class="mb-0"><b>{{ $invoices->total() }} hóa đơn</b></h3>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Tổng doanh thu thực thu</h6>
                <h3 class="mb-0"><b>{{ number_format($totalRevenue, 0, ',', '.') }} đ</b></h3>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <b>Danh sách hóa đơn thanh toán</b>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Mã HĐ</th>
                    <th>Khách hàng</th>
                    <th>Phòng</th>
                    <th>Tiền phòng</th>
                    <th>Tiền dịch vụ</th>
                    <th>Tổng tiền</th>
                    <th>Hình thức</th>
                    <th>Ngày thanh toán</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @if (count($invoices) > 0)
                @foreach ($invoices as $row)
                <tr>
                    <td><span class="badge bg-primary fs-6">{{ $row->InvoiceCode }}</span></td>
                    <td><b>{{ $row->booking ? $row->booking->GuestName : 'Khách vãng lai' }}</b></td>
                    <td>{{ $row->booking && $row->booking->room ? 'Phòng ' . $row->booking->room->RoomNumber : '-' }}</td>
                    <td>{{ number_format($row->RoomAmount, 0, ',', '.') }} đ</td>
                    <td>{{ number_format($row->ServiceAmount, 0, ',', '.') }} đ</td>
                    <td class="text-danger"><b>{{ number_format($row->TotalAmount, 0, ',', '.') }} đ</b></td>
                    <td>
                        @if ($row->PaymentMethod == 'tien_mat')
                        <span class="badge bg-secondary">Tiền mặt</span>
                        @elseif ($row->PaymentMethod == 'chuyen_khoan')
                        <span class="badge bg-info text-dark">Chuyển khoản</span>
                        @else
                        <span class="badge bg-warning text-dark">Thẻ</span>
                        @endif
                    </td>
                    <td>{{ $row->PaymentDate->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('invoices.show', $row->InvoiceID) }}" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="9" class="text-center text-muted">Chưa có hóa đơn nào được xuất</td>
                </tr>
                @endif
            </tbody>
        </table>
        {!! $invoices->links() !!}
    </div>
</div>

@endsection

