@extends('layouts.master')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><b>Sửa thông tin dịch vụ</b></h5>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('services.update', $service->ServiceID) }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <label class="col-sm-3 col-label-form"><b>Tên dịch vụ</b></label>
                        <div class="col-sm-9">
                            <input type="text" name="ServiceName" class="form-control @error('ServiceName') is-invalid @enderror" value="{{ old('ServiceName', $service->ServiceName) }}" />
                            @error('ServiceName')
                            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-label-form"><b>Đơn giá (VNĐ)</b></label>
                        <div class="col-sm-9">
                            <input type="number" name="Price" class="form-control @error('Price') is-invalid @enderror" value="{{ old('Price', $service->Price) }}" min="0" step="1000" />
                            @error('Price')
                            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-label-form"><b>Đơn vị tính</b></label>
                        <div class="col-sm-9">
                            <input type="text" name="Unit" class="form-control @error('Unit') is-invalid @enderror" value="{{ old('Unit', $service->Unit) }}" />
                            @error('Unit')
                            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-label-form"><b>Mô tả</b></label>
                        <div class="col-sm-9">
                            <textarea name="Description" class="form-control" rows="3">{{ old('Description', $service->Description) }}</textarea>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ route('services.index') }}" class="btn btn-secondary me-2">Quay lại</a>
                        <input type="submit" class="btn btn-primary" value="Lưu thay đổi" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

