<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bookings.index', ['bookings' => Booking::with('room')->paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rooms = Room::all();
        return view('bookings.create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $RoomID = $request->input('RoomID');
        $GuestName = $request->input('GuestName');
        $GuestPhone = $request->input('GuestPhone');
        $GuestEmail = $request->input('GuestEmail');
        $CheckInDate = $request->input('CheckInDate');
        $CheckOutDate = $request->input('CheckOutDate') ?: null;
        $BookingStatus = $request->input('BookingStatus');

        $request->validate([
            'RoomID' => 'required|exists:rooms,RoomID',
            'GuestName' => 'required',
            'GuestPhone' => ['required', 'regex:/^0[0-9]{9}$/'],
            'GuestEmail' => 'nullable|email',
            'CheckInDate' => 'required|date|after_or_equal:today',
            'CheckOutDate' => 'nullable|required_unless:BookingStatus,1|date|after:CheckInDate',
            'BookingStatus' => 'required|in:0,1,2',
        ], [
            'RoomID.required' => 'Vui lòng chọn phòng.',
            'RoomID.exists' => 'Phòng được chọn không tồn tại.',
            'GuestName.required' => 'Vui lòng nhập họ tên khách.',
            'GuestPhone.required' => 'Vui lòng nhập số điện thoại.',
            'GuestPhone.regex' => 'Số điện thoại không hợp lệ (gồm 10 số, bắt đầu bằng số 0).',
            'GuestEmail.email' => 'Địa chỉ Email không đúng định dạng.',
            'CheckInDate.required' => 'Vui lòng chọn ngày nhận phòng.',
            'CheckInDate.date' => 'Ngày nhận phòng không đúng định dạng ngày.',
            'CheckInDate.after_or_equal' => 'Ngày nhận phòng không thể là ngày trong quá khứ.',
            'CheckOutDate.required' => 'Vui lòng chọn ngày trả phòng.',
            'CheckOutDate.required_unless' => 'Vui lòng chọn ngày trả phòng (chỉ đơn "Đang ở" mới được để trống).',
            'CheckOutDate.date' => 'Ngày trả phòng không đúng định dạng ngày.',
            'CheckOutDate.after' => 'Ngày trả phòng phải sau ngày nhận phòng.',
            'BookingStatus.required' => 'Vui lòng chọn trạng thái.',
            'BookingStatus.in' => 'Trạng thái đặt phòng không hợp lệ.',
        ]);

        $newCheckIn = $CheckInDate;
        $newCheckOut = $CheckOutDate ?: '2099-12-31';

        $isOverlap = Booking::where('RoomID', $RoomID)
            ->where('BookingStatus', '!=', '2')
            ->where(function ($query) use ($newCheckIn, $newCheckOut) {
                $query->where('CheckInDate', '<', $newCheckOut)
                      ->whereRaw("COALESCE(CheckOutDate, '2099-12-31') > ?", [$newCheckIn]);
            })
            ->exists();

        if ($isOverlap) {
            return back()->withInput()->withErrors([
                'RoomID' => 'Phòng này đã có khách đặt hoặc đang ở trong khoảng thời gian đã chọn.'
            ]);
        }

        Booking::create([
            'RoomID' => $RoomID,
            'GuestName' => $GuestName,
            'GuestPhone' => $GuestPhone,
            'GuestEmail' => $GuestEmail,
            'CheckInDate' => $CheckInDate,
            'CheckOutDate' => $CheckOutDate,
            'BookingStatus' => $BookingStatus,
        ]);

        $this->syncRoomStatus($RoomID);

        return redirect()->route('bookings.index')->with('success', 'Đã ghi nhận đặt phòng');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $rooms = Room::all();
        return view('bookings.edit', [
            'rooms' => $rooms,
            'booking' => $booking,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $RoomID = $request->input('RoomID');
        $GuestName = $request->input('GuestName');
        $GuestPhone = $request->input('GuestPhone');
        $GuestEmail = $request->input('GuestEmail');
        $CheckInDate = $request->input('CheckInDate');
        $CheckOutDate = $request->input('CheckOutDate') ?: null;
        $BookingStatus = $request->input('BookingStatus');

        $request->validate([
            'RoomID' => 'required|exists:rooms,RoomID',
            'GuestName' => 'required',
            'GuestPhone' => ['required', 'regex:/^0[0-9]{9}$/'],
            'GuestEmail' => 'nullable|email',
            'CheckInDate' => 'required|date',
            'CheckOutDate' => 'nullable|required_unless:BookingStatus,1|date|after:CheckInDate',
            'BookingStatus' => 'required|in:0,1,2',
        ], [
            'RoomID.required' => 'Vui lòng chọn phòng.',
            'RoomID.exists' => 'Phòng được chọn không tồn tại.',
            'GuestName.required' => 'Vui lòng nhập họ tên khách.',
            'GuestPhone.required' => 'Vui lòng nhập số điện thoại.',
            'GuestPhone.regex' => 'Số điện thoại không hợp lệ (gồm 10 số, bắt đầu bằng số 0).',
            'GuestEmail.email' => 'Địa chỉ Email không đúng định dạng.',
            'CheckInDate.required' => 'Vui lòng chọn ngày nhận phòng.',
            'CheckInDate.date' => 'Ngày nhận phòng không đúng định dạng ngày.',
            'CheckOutDate.required' => 'Vui lòng chọn ngày trả phòng.',
            'CheckOutDate.required_unless' => 'Vui lòng chọn ngày trả phòng (chỉ đơn "Đang ở" mới được để trống).',
            'CheckOutDate.date' => 'Ngày trả phòng không đúng định dạng ngày.',
            'CheckOutDate.after' => 'Ngày trả phòng phải sau ngày nhận phòng.',
            'BookingStatus.required' => 'Vui lòng chọn trạng thái.',
            'BookingStatus.in' => 'Trạng thái đặt phòng không hợp lệ.',
        ]);

        $newCheckIn = $CheckInDate;
        $newCheckOut = $CheckOutDate ?: '2099-12-31';

        $isOverlap = Booking::where('RoomID', $RoomID)
            ->where('BookingID', '!=', $booking->BookingID)
            ->where('BookingStatus', '!=', '2')
            ->where(function ($query) use ($newCheckIn, $newCheckOut) {
                $query->where('CheckInDate', '<', $newCheckOut)
                      ->whereRaw("COALESCE(CheckOutDate, '2099-12-31') > ?", [$newCheckIn]);
            })
            ->exists();

        if ($isOverlap) {
            return back()->withInput()->withErrors([
                'RoomID' => 'Phòng này đã có khách đặt hoặc đang ở trong khoảng thời gian đã chọn.'
            ]);
        }

        $oldRoomId = $booking->RoomID;

        $booking->update([
            'RoomID' => $RoomID,
            'GuestName' => $GuestName,
            'GuestPhone' => $GuestPhone,
            'GuestEmail' => $GuestEmail,
            'CheckInDate' => $CheckInDate,
            'CheckOutDate' => $CheckOutDate,
            'BookingStatus' => $BookingStatus,
        ]);

        $this->syncRoomStatus($RoomID);

        if ($oldRoomId != $RoomID) {
            $this->syncRoomStatus($oldRoomId);
        }

        return redirect()->route('bookings.index')->with('success', 'Đã cập nhật đặt phòng');
    }

    /**
     * Show the page asking before deleting the resource.
     */
    public function delete(Booking $booking)
    {
        return view('bookings.delete', compact('booking'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $roomId = $booking->RoomID;

        $booking->delete();

        $this->syncRoomStatus($roomId);

        return redirect()->route('bookings.index')->with('success', 'Đã xóa đặt phòng');
    }

    private function syncRoomStatus($roomId)
    {
        Room::syncStatus($roomId);
    }
}
