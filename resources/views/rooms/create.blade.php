@extends('layouts.master')

@section('content')

<div class="card">
    <div class="card-header"><b>Thêm phòng mới</b></div>
    <div class="card-body">
        <form method="post" action="{{ route('rooms.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Số phòng</label>
                <div class="col-sm-10">
                    <input type="text" name="RoomNumber" class="form-control @error('RoomNumber') is-invalid @enderror" value="{{ old('RoomNumber') }}" maxlength="3" placeholder="Nhập 3 chữ số (VD: 101)" />
                    @error('RoomNumber')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-4">
                <label class="col-sm-2 col-label-form">Loại phòng</label>
                <div class="col-sm-10">
                    <select name="RoomType" class="form-control @error('RoomType') is-invalid @enderror">
                        <option value="don" {{ old('RoomType') == 'don' ? 'selected' : '' }}>Phòng đơn</option>
                        <option value="doi" {{ old('RoomType') == 'doi' ? 'selected' : '' }}>Phòng đôi</option>
                        <option value="vip" {{ old('RoomType') == 'vip' ? 'selected' : '' }}>Phòng VIP</option>
                    </select>
                    @error('RoomType')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Giá phòng (Đ/Đêm)</label>
                <div class="col-sm-10">
                    <input type="number" name="RoomPrice" class="form-control @error('RoomPrice') is-invalid @enderror" min="0" step="10000"
                        value="{{ old('RoomPrice') }}" />
                    @error('RoomPrice')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Sức chứa (Người)</label>
                <div class="col-sm-10">
                    <input type="number" name="RoomCapacity" class="form-control @error('RoomCapacity') is-invalid @enderror" min="1" max="20"
                        value="{{ old('RoomCapacity', 2) }}" />
                    @error('RoomCapacity')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-4">
                <label class="col-sm-2 col-label-form">Trạng thái</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" value="Trống" readonly />
                    <small class="text-muted">Phòng mới luôn ở trạng thái Trống, trạng thái sẽ tự đổi khi có đơn đặt phòng.</small>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Mô tả</label>
                <div class="col-sm-10">
                    <textarea name="RoomDescription" class="form-control @error('RoomDescription') is-invalid @enderror" rows="3">{{ old('RoomDescription') }}</textarea>
                    @error('RoomDescription')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('rooms.index') }}" class="btn btn-secondary me-2">Quay lại</a>
                <input type="submit" class="btn btn-success" value="Thêm phòng" />
            </div>
        </form>
    </div>
</div>

@endsection
