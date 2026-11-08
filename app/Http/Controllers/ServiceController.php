<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return view('services.index', ['services' => Service::paginate(10)]);
    }

    public function show(Service $service)
    {
        $service->load(['bookings.room']);
        return view('services.show', compact('service'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ServiceName' => 'required|max:255|unique:services,ServiceName',
            'Price' => 'required|integer|min:0',
            'Unit' => 'required|max:50',
            'Description' => 'nullable',
        ], [
            'ServiceName.required' => 'Vui lòng nhập tên dịch vụ.',
            'ServiceName.unique' => 'Tên dịch vụ này đã tồn tại trong hệ thống.',
            'Price.required' => 'Vui lòng nhập giá dịch vụ.',
            'Price.integer' => 'Giá dịch vụ phải là số nguyên.',
            'Price.min' => 'Giá dịch vụ không được nhỏ hơn 0.',
            'Unit.required' => 'Vui lòng nhập đơn vị tính.',
        ]);

        Service::create([
            'ServiceName' => $request->input('ServiceName'),
            'Price' => $request->input('Price'),
            'Unit' => $request->input('Unit'),
            'Description' => $request->input('Description'),
        ]);

        return redirect()->route('services.index')->with('success', 'Đã thêm dịch vụ mới');
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'ServiceName' => 'required|max:255|unique:services,ServiceName,' . $service->ServiceID . ',ServiceID',
            'Price' => 'required|integer|min:0',
            'Unit' => 'required|max:50',
            'Description' => 'nullable',
        ], [
            'ServiceName.required' => 'Vui lòng nhập tên dịch vụ.',
            'ServiceName.unique' => 'Tên dịch vụ này đã tồn tại trong hệ thống.',
            'Price.required' => 'Vui lòng nhập giá dịch vụ.',
            'Price.integer' => 'Giá dịch vụ phải là số nguyên.',
            'Price.min' => 'Giá dịch vụ không được nhỏ hơn 0.',
            'Unit.required' => 'Vui lòng nhập đơn vị tính.',
        ]);

        $service->update([
            'ServiceName' => $request->input('ServiceName'),
            'Price' => $request->input('Price'),
            'Unit' => $request->input('Unit'),
            'Description' => $request->input('Description'),
        ]);

        return redirect()->route('services.index')->with('success', 'Đã cập nhật dịch vụ');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Đã xóa dịch vụ');
    }
}

