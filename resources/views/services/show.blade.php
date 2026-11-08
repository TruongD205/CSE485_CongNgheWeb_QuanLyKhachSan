@extends('layouts.master')

@section('content')

<div class="card">
    <div class="card-header">
        <b>Thông tin dịch vụ chi tiết</b>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Mã dịch vụ</b></label>
            <div class="col-sm-10">
                {{ $service->ServiceID }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Tên dịch vụ</b></label>
            <div class="col-sm-10">
                {{ $service->ServiceName }}
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Đơn giá</b></label>
            <div class="col-sm-10">
                {{ number_format($service->Price, 0, ',', '.') }} Đ
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-2 col-label-form"><b>Đơn vị tính</b></label>
            <div class="col-sm-10">
                {{ $service->Unit }}
            </div>
        </div>
        <div class="row mb-4">
            <label class="col-sm-2 col-label-form"><b>Mô tả</b></label>
            <div class="col-sm-10">
                {{ $service->Description ?: 'Không có mô tả' }}
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('services.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <b>Danh sách khách hàng đã sử dụng dịch vụ {{ $service->ServiceName }}</b>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Mã ĐP</th>
                <th>Khách hàng</th>
                <th>Phòng</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
            @if ($service->bookings && $service->bookings->count() > 0)
            @foreach ($service->bookings as $booking)
            <tr>
                <td>{{ $booking->BookingID }}</td>
                <td>{{ $booking->GuestName }}</td>
                <td>{{ $booking->room ? 'Phòng ' . $booking->room->RoomNumber : '-' }}</td>
                <td>{{ $booking->pivot->Quantity }} {{ $service->Unit }}</td>
                <td>{{ number_format($booking->pivot->Price, 0, ',', '.') }} Đ</td>
                <td>{{ number_format($booking->pivot->TotalPrice, 0, ',', '.') }} Đ</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="6" class="text-center">Chưa có dữ liệu</td>
            </tr>
            @endif
        </table>
    </div>
</div>

@endsection

