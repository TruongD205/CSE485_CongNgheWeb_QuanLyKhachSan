@extends('layouts.master')

@section('content')

<div class="card">
    <div class="card-header bg-danger text-white"><b>Xác nhận xoá phòng {{ $room->RoomNumber }}</b></div>
    <div class="card-body">

        @if ($message = Session::get('error'))
        <div class="alert alert-danger">{{ $message }}</div>
        @endif

        @if ($activeBookings->isNotEmpty())
        <div class="alert alert-danger shadow-sm border-danger">
            <h5 class="alert-heading text-danger"><b>PHÒNG ĐANG CÓ ĐƠN GIỮ CHỖ - KHÔNG THỂ XOÁ!</b></h5>
            <p class="mb-1">
                Phòng <b>{{ $room->RoomNumber }}</b> đang có {{ $activeBookings->count() }} đơn chưa trả phòng:
                @foreach ($activeBookings as $activeBooking)
                <br>• <b>{{ $activeBooking->BookingStatus == '1' ? 'Đang ở' : 'Đã xác nhận' }}</b>:
                <b>{{ $activeBooking->GuestName }}</b> (SĐT: <b>{{ $activeBooking->GuestPhone }}</b>) -
                {{ $activeBooking->CheckInDate->format('d/m/Y') }} đến
                <b>{{ $activeBooking->CheckOutDate ? $activeBooking->CheckOutDate->format('d/m/Y') : 'Chưa xác định' }}</b>
                @endforeach
            </p>
        </div>
        @else
        <div class="alert alert-warning shadow-sm border-warning">
            <h5 class="alert-heading text-warning"><b>LƯU Ý TRƯỚC KHI XOÁ</b></h5>
            <p class="mb-1">
                Phòng <b>{{ $room->RoomNumber }}</b> hiện không có khách nào đang ở hay đặt trước.
                @if ($historyCount > 0)
                <br>• Sẽ xoá theo <b>{{ $historyCount }}</b> đơn đặt phòng cũ trong lịch sử (đã trả phòng).
                @endif
            </p>
            <small class="text-dark">Hành động này không thể hoàn tác.</small>
        </div>
        @endif

        <form method="post" action="{{ route('rooms.destroy', $room->RoomID) }}"
            onsubmit="return confirm('Bạn có chắc chắn muốn xoá phòng {{ $room->RoomNumber }} không?');">
            @csrf
            @method('DELETE')
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Số phòng</label>
                <div class="col-sm-10">
                    <input type="text" name="RoomNumber" class="form-control" value="{{ $room->RoomNumber }}" readonly />
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Loại phòng</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control"
                        value="{{ $room->RoomType == 'don' ? 'Phòng đơn' : ($room->RoomType == 'doi' ? 'Phòng đôi' : 'Phòng VIP') }}" readonly />
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Giá phòng (đ/đêm)</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" value="{{ number_format($room->RoomPrice, 0, ',', '.') }} đ" readonly />
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Sức chứa (người)</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" value="{{ $room->RoomCapacity }}" readonly />
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Trạng thái</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control"
                        value="{{ $room->RoomStatus == '0' ? 'Trống' : ($room->RoomStatus == '1' ? 'Đã đặt' : 'Đang ở') }}" readonly />
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Mô tả</label>
                <div class="col-sm-10">
                    <textarea class="form-control" rows="3" readonly>{{ $room->RoomDescription }}</textarea>
                </div>
            </div>
            <div class="text-center">
                <a href="{{ route('rooms.index') }}" class="btn btn-secondary me-2">Quay lại</a>
                <input type="submit" class="btn btn-danger" value="Xoá" @if ($activeBookings->isNotEmpty()) disabled @endif />
            </div>
        </form>
    </div>
</div>

@endsection
