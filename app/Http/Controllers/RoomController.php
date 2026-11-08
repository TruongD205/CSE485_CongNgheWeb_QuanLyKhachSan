<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('rooms.index', ['rooms' => Room::paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $RoomNumber = $request->input('RoomNumber');
        $RoomType = $request->input('RoomType');
        $RoomPrice = $request->input('RoomPrice');
        $RoomCapacity = $request->input('RoomCapacity');
        $RoomDescription = $request->input('RoomDescription');

        $request->validate([
            'RoomNumber' => 'required|digits:3|unique:rooms,RoomNumber',
            'RoomType' => 'required|in:don,doi,vip',
            'RoomPrice' => 'required|integer|min:0',
            'RoomCapacity' => 'required|integer|min:1',
            'RoomDescription' => 'nullable',
        ], [
            'RoomNumber.required' => 'Vui lòng nhập số phòng.',
            'RoomNumber.digits' => 'Số phòng phải gồm đúng 3 chữ số (ví dụ: 101, 201).',
            'RoomNumber.unique' => 'Số phòng này đã tồn tại trong hệ thống.',
            'RoomType.required' => 'Vui lòng chọn loại phòng.',
            'RoomType.in' => 'Loại phòng đã chọn không hợp lệ.',
            'RoomPrice.required' => 'Vui lòng nhập giá phòng.',
            'RoomPrice.integer' => 'Giá phòng phải là số nguyên.',
            'RoomPrice.min' => 'Giá phòng không được nhỏ hơn 0.',
            'RoomCapacity.required' => 'Vui lòng nhập sức chứa.',
            'RoomCapacity.integer' => 'Sức chứa phải là số nguyên.',
            'RoomCapacity.min' => 'Sức chứa tối thiểu là 1 người.',
        ]);

        Room::create([
            'RoomNumber' => $RoomNumber,
            'RoomType' => $RoomType,
            'RoomPrice' => $RoomPrice,
            'RoomCapacity' => $RoomCapacity,
            'RoomStatus' => '0',
            'RoomDescription' => $RoomDescription,
        ]);

        return redirect()->route('rooms.index')->with('success', 'Đã thêm phòng mới');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        $room->load(['bookings.invoice']);
        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $RoomNumber = $request->input('RoomNumber');
        $RoomType = $request->input('RoomType');
        $RoomPrice = $request->input('RoomPrice');
        $RoomCapacity = $request->input('RoomCapacity');
        $RoomDescription = $request->input('RoomDescription');

        $request->validate([
            'RoomNumber' => 'required|digits:3|unique:rooms,RoomNumber,' . $room->RoomID . ',RoomID',
            // khin người dùng ấn sửa mà giữ nguyên phòng cũ hệ thống vẫn cho qua mà ko báo lỗi trùng số phòng
            'RoomType' => 'required|in:don,doi,vip',
            'RoomPrice' => 'required|integer|min:0',
            'RoomCapacity' => 'required|integer|min:1',
            'RoomDescription' => 'nullable',
        ], [
            'RoomNumber.required' => 'Vui lòng nhập số phòng.',
            'RoomNumber.digits' => 'Số phòng phải gồm đúng 3 chữ số (ví dụ: 101, 201).',
            'RoomNumber.unique' => 'Số phòng này đã tồn tại trong hệ thống.',
            'RoomType.required' => 'Vui lòng chọn loại phòng.',
            'RoomType.in' => 'Loại phòng đã chọn không hợp lệ.',
            'RoomPrice.required' => 'Vui lòng nhập giá phòng.',
            'RoomPrice.integer' => 'Giá phòng phải là số nguyên.',
            'RoomPrice.min' => 'Giá phòng không được nhỏ hơn 0.',
            'RoomCapacity.required' => 'Vui lòng nhập sức chứa.',
            'RoomCapacity.integer' => 'Sức chứa phải là số nguyên.',
            'RoomCapacity.min' => 'Sức chứa tối thiểu là 1 người.',
        ]);

        $room->update([
            'RoomNumber' => $RoomNumber,
            'RoomType' => $RoomType,
            'RoomPrice' => $RoomPrice,
            'RoomCapacity' => $RoomCapacity,
            'RoomDescription' => $RoomDescription,
        ]);

        return redirect()->route('rooms.index')->with('success', 'Đã cập nhật thông tin phòng');
    }

    /**
     * Show the page asking before deleting the resource.
     */
    public function delete(Room $room)
    {
        $activeBookings = $room->activeBookings()->orderBy('CheckInDate')->get();
        $historyCount = $room->bookings()->where('BookingStatus', '2')->count();
        $hasInvoice = $room->bookings()->whereHas('invoice')->exists();

        return view('rooms.delete', compact('room', 'activeBookings', 'historyCount', 'hasInvoice'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        if ($room->activeBookings()->exists()) {
            return redirect()->route('rooms.delete', $room->RoomID)
                ->with('error', 'Không thể xoá phòng ' . $room->RoomNumber . ' vì còn đơn đặt phòng "Đã xác nhận" hoặc "Đang ở". Hãy trả phòng hoặc xoá các đơn đó trước.');
        }

        if ($room->bookings()->whereHas('invoice')->exists()) {
            return redirect()->route('rooms.delete', $room->RoomID)
                ->with('error', 'Không thể xoá phòng ' . $room->RoomNumber . ' vì phòng đã phát sinh lịch sử hóa đơn tài chính!');
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Đã xóa phòng');
    }
}
