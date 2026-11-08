@extends('layouts.master')

@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
    {{ $message }}
</div>
@endif

<div class="container mt-5">
    <h1 class="text-primary mt-3 mb-4 text-center"><b>Quản lý hóa đơn</b></h1>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col col-md-6"><b>Danh sách hóa đơn</b></div>
            <div class="col col-md-6 text-end">
                <span class="fs-6 fw-bold">Tổng doanh thu:</span>
                <span class="fs-5 text-danger fw-bold ms-2">{{ number_format($totalRevenue, 0, ',', '.') }} Đ</span>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Mã HĐ</th>
                <th>Khách hàng</th>
                <th>Phòng</th>
                <th>Tiền phòng</th>
                <th>Tiền dịch vụ</th>
                <th>Tổng tiền</th>
                <th>Hình thức</th>
                <th>Trạng thái</th>
                <th>Ngày thanh toán</th>
                <th>Thao tác</th>
            </tr>
            @if (count($invoices) > 0)
            @foreach ($invoices as $row)
            <tr>
                <td>{{ $row->InvoiceCode }}</td>
                <td>{{ $row->booking ? $row->booking->GuestName : 'Khách vãng lai' }}</td>
                <td>{{ $row->booking && $row->booking->room ? 'Phòng ' . $row->booking->room->RoomNumber : '-' }}</td>
                <td>{{ number_format($row->RoomAmount, 0, ',', '.') }} Đ</td>
                <td>{{ number_format($row->ServiceAmount, 0, ',', '.') }} Đ</td>
                <td>{{ number_format($row->TotalAmount, 0, ',', '.') }} Đ</td>
                <td>
                    @if ($row->PaymentMethod == 'tien_mat')
                    Tiền mặt
                    @elseif ($row->PaymentMethod == 'chuyen_khoan')
                    Chuyển khoản
                    @else
                    Thẻ
                    @endif
                </td>
                <td>
                    @if ($row->PaymentStatus == 1)
                    Đã thanh toán
                    @else
                    Chưa thanh toán
                    @endif
                </td>
                <td>{{ $row->PaymentDate ? \Carbon\Carbon::parse($row->PaymentDate)->format('d/m/Y H:i:s') : '-' }}</td>
                <td>
                    <a href="{{ route('invoices.show', $row->InvoiceID) }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="10" class="text-center">Chưa có dữ liệu</td>
            </tr>
            @endif
        </table>
        {!! $invoices->links() !!}
    </div>
</div>

@endsection
