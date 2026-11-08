@extends('layouts.master')

@section('content')

<div class="card">
    <div class="card-header"><b>Thêm đặt phòng mới</b></div>
    <div class="card-body">
        <form method="post" action="{{ route('bookings.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <label for="RoomID" class="col-sm-2 col-label-form">Chọn phòng</label>
                <div class="col-sm-10">
                    <select id="RoomID" class="form-select @error('RoomID') is-invalid @enderror" name="RoomID" required>
                        @foreach ($rooms as $room)
                        <option value="{{ $room->RoomID }}" {{ old('RoomID') == $room->RoomID ? 'selected' : '' }}>
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
                    <input type="text" name="GuestName" class="form-control @error('GuestName') is-invalid @enderror" value="{{ old('GuestName') }}" />
                    @error('GuestName')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Điện thoại</label>
                <div class="col-sm-10">
                    <input type="text" name="GuestPhone" class="form-control @error('GuestPhone') is-invalid @enderror" value="{{ old('GuestPhone') }}" />
                    @error('GuestPhone')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Địa chỉ Email</label>
                <div class="col-sm-10">
                    <input type="text" name="GuestEmail" class="form-control @error('GuestEmail') is-invalid @enderror" value="{{ old('GuestEmail') }}" />
                    @error('GuestEmail')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Ngày nhận phòng</label>
                <div class="col-sm-10">
                    <input type="date" name="CheckInDate" class="form-control @error('CheckInDate') is-invalid @enderror" value="{{ old('CheckInDate') }}" />
                    @error('CheckInDate')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Ngày trả phòng</label>
                <div class="col-sm-10">
                    <input type="date" name="CheckOutDate" class="form-control @error('CheckOutDate') is-invalid @enderror" value="{{ old('CheckOutDate') }}" />
                    <small class="text-muted">Bỏ trống nếu khách đang ở và chưa xác định ngày trả phòng.</small>
                    @error('CheckOutDate')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Trạng thái</label>
                <div class="col-sm-10">
                    <select name="BookingStatus" class="form-control @error('BookingStatus') is-invalid @enderror">
                        <option value="0" {{ old('BookingStatus') == '0' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="1" {{ old('BookingStatus') == '1' ? 'selected' : '' }}>Đang ở</option>
                        <option value="2" {{ old('BookingStatus') == '2' ? 'selected' : '' }}>Đã trả phòng</option>
                    </select>
                    @error('BookingStatus')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-4">
                <label class="col-sm-2 col-label-form">Dịch vụ sử dụng</label>
                <div class="col-sm-10">
                    @if (count($services) > 0)
                    <table class="table table-bordered align-middle mb-1">
                        <thead class="table-light">
                            <tr>
                                <th>Tên dịch vụ</th>
                                <th>Đơn giá</th>
                                <th>Đơn vị tính</th>
                                <th style="width: 140px;">Số lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                            <tr>
                                <td>{{ $service->ServiceName }}</td>
                                <td>{{ number_format($service->Price, 0, ',', '.') }} Đ</td>
                                <td>{{ $service->Unit }}</td>
                                <td>
                                    <input type="number" name="Services[{{ $service->ServiceID }}]" aria-label="Số lượng {{ $service->ServiceName }}" class="form-control form-control-sm @error('Services.' . $service->ServiceID) is-invalid @enderror" value="{{ old('Services.' . $service->ServiceID, 0) }}" min="0" max="99" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <small class="text-muted">Nhập số lượng cho dịch vụ khách muốn dùng, để 0 nếu không dùng.</small>
                    @error('Services')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                    @error('Services.*')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                    @else
                    <span class="text-muted">Chưa có dịch vụ nào.</span>
                    @endif
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('bookings.index') }}" class="btn btn-secondary me-2">Quay lại</a>
                <input type="submit" class="btn btn-success" value="Thêm đặt phòng" />
            </div>
        </form>
    </div>
</div>

@endsection
