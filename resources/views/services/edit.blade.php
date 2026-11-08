@extends('layouts.master')

@section('content')

<div class="card">
    <div class="card-header"><b>Sửa thông tin dịch vụ</b></div>
    <div class="card-body">
        <form method="post" action="{{ route('services.update', $service->ServiceID) }}">
            @csrf
            @method('PUT')
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Tên dịch vụ</label>
                <div class="col-sm-10">
                    <input type="text" name="ServiceName" class="form-control @error('ServiceName') is-invalid @enderror" value="{{ old('ServiceName', $service->ServiceName) }}" />
                    @error('ServiceName')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Đơn giá (đ)</label>
                <div class="col-sm-10">
                    <input type="number" name="Price" class="form-control @error('Price') is-invalid @enderror" value="{{ old('Price', $service->Price) }}" min="0" step="1000" />
                    @error('Price')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-2 col-label-form">Đơn vị tính</label>
                <div class="col-sm-10">
                    <input type="text" name="Unit" class="form-control @error('Unit') is-invalid @enderror" value="{{ old('Unit', $service->Unit) }}" />
                    @error('Unit')
                    <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
            </div>
            <div class="row mb-4">
                <label class="col-sm-2 col-label-form">Mô tả</label>
                <div class="col-sm-10">
                    <textarea name="Description" class="form-control" rows="3">{{ old('Description', $service->Description) }}</textarea>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('services.index') }}" class="btn btn-secondary me-2">Quay lại</a>
                <input type="submit" class="btn btn-warning" value="Lưu thay đổi" />
            </div>
        </form>
    </div>
</div>

@endsection
