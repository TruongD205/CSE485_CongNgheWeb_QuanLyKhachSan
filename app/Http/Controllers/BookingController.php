<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Room;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $services = Service::all();
        return view('bookings.create', compact('rooms', 'services'));
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
        $Services = $request->input('Services') ?: [];

        $request->validate([
            'RoomID' => 'required|exists:rooms,RoomID',
            'GuestName' => 'required',
            'GuestPhone' => ['required', 'regex:/^0[0-9]{9}$/'],
            'GuestEmail' => 'nullable|email',
            'CheckInDate' => 'required|date|after_or_equal:today',
            'CheckOutDate' => 'nullable|required_unless:BookingStatus,1|date|after:CheckInDate',
            'BookingStatus' => 'required|in:0,1,2',
            'Services' => 'nullable|array',
            'Services.*' => 'nullable|integer|min:0|max:99',
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
            'Services.array' => 'Danh sách dịch vụ không hợp lệ.',
            'Services.*.integer' => 'Số lượng dịch vụ phải là số nguyên.',
            'Services.*.min' => 'Số lượng dịch vụ không được nhỏ hơn 0.',
            'Services.*.max' => 'Số lượng dịch vụ tối đa là 99.',
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

        $booking = Booking::create([
            'RoomID' => $RoomID,
            'GuestName' => $GuestName,
            'GuestPhone' => $GuestPhone,
            'GuestEmail' => $GuestEmail,
            'CheckInDate' => $CheckInDate,
            'CheckOutDate' => $CheckOutDate,
            'BookingStatus' => $BookingStatus,
        ]);

        foreach (Service::whereIn('ServiceID', array_keys($Services))->get() as $service) {
            $quantity = (int) $Services[$service->ServiceID];

            if ($quantity > 0) {
                $this->attachService($booking, $service, $quantity);
            }
        }

        $this->syncRoomStatus($RoomID);

        if ($BookingStatus == '2') {
            $this->createInvoice($booking);
        }

        return redirect()->route('bookings.index')->with('success', 'Đã ghi nhận đặt phòng');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $booking->load(['room', 'services', 'invoice']);
        $availableServices = Service::all();

        return view('bookings.show', compact('booking', 'availableServices'));
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
                                // trong CSDL   // biến người dùng gõ
        $isOverlap = Booking::where('RoomID', $RoomID) // tìm những đơn đặt phòng của căn phòng này
            ->where('BookingID', '!=', $booking->BookingID) // loại trừ đơn hiện tại đang sửa
            ->where('BookingStatus', '!=', '2') // loại trừ đơ có trạng thái đã trả
            ->where(function ($query) use ($newCheckIn, $newCheckOut) { // kiểm tra xem có thời gian đè lên nhau ko
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
        $oldStatus = $booking->BookingStatus;

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

        if ($BookingStatus == '2' && ($oldStatus != '2' || !$booking->invoice()->exists())) {
            $this->createInvoice($booking);
        }

        return redirect()->route('bookings.index')->with('success', 'Đã cập nhật đặt phòng');
    }

    /**
     * Show the page asking before deleting the resource.
     */
    public function delete(Booking $booking)
    {
        $hasInvoice = $booking->invoice()->exists();
        return view('bookings.delete', compact('booking', 'hasInvoice'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        if ($booking->invoice()->exists()) {
            return back()->with('error', 'Đơn đặt phòng này đã xuất hóa đơn thanh toán, không thể xoá để bảo toàn số liệu kế toán!');
        }

        $roomId = $booking->RoomID;

        $booking->delete();

        $this->syncRoomStatus($roomId);

        return redirect()->route('bookings.index')->with('success', 'Đã xóa đặt phòng');
    }

    public function checkout(Booking $booking)
    {
        $booking->load(['room', 'services', 'invoice']);

        $defaultCheckOutDate = $booking->CheckOutDate
            ? (string) $booking->CheckOutDate
            : now()->format('Y-m-d');

        if ($defaultCheckOutDate < (string) $booking->CheckInDate) {
            $defaultCheckOutDate = (string) $booking->CheckInDate;
        }

        $checkIn = \Carbon\Carbon::parse($booking->CheckInDate);
        $checkOut = \Carbon\Carbon::parse($defaultCheckOutDate);
        $nights = max(1, (int) $checkIn->diffInDays($checkOut));

        $roomAmount = $nights * $booking->room->RoomPrice;
        $serviceAmount = (int) $booking->services->sum('pivot.TotalPrice');
        $totalAmount = $roomAmount + $serviceAmount;

        return view('bookings.checkout', compact('booking', 'defaultCheckOutDate', 'nights', 'roomAmount', 'serviceAmount', 'totalAmount'));
    }

    public function processCheckout(Request $request, Booking $booking)
    {
        $checkInYmd = \Carbon\Carbon::parse($booking->CheckInDate)->format('Y-m-d');
        $request->validate([
            'CheckOutDate' => 'required|date|after_or_equal:' . $checkInYmd,
            'PaymentMethod' => 'required|in:tien_mat,chuyen_khoan,the',
            'PaymentStatus' => 'required|in:0,1',
        ], [
            'CheckOutDate.required' => 'Vui lòng chọn ngày trả phòng.',
            'CheckOutDate.date' => 'Ngày trả phòng không đúng định dạng.',
            'CheckOutDate.after_or_equal' => 'Ngày trả phòng không được trước ngày nhận phòng.',
            'PaymentMethod.required' => 'Vui lòng chọn hình thức thanh toán.',
            'PaymentStatus.required' => 'Vui lòng chọn trạng thái thanh toán.',
        ]);

        $checkIn = \Carbon\Carbon::parse($booking->CheckInDate);
        $checkOut = \Carbon\Carbon::parse($request->input('CheckOutDate'));
        $nights = max(1, (int) $checkIn->diffInDays($checkOut));

        $roomAmount = $nights * $booking->room->RoomPrice;
        $serviceAmount = (int) $booking->services()->sum('TotalPrice');
        $totalAmount = $roomAmount + $serviceAmount;

        $booking->update([
            'CheckOutDate' => $request->input('CheckOutDate'),
            'BookingStatus' => '2',
        ]);

        $this->syncRoomStatus($booking->RoomID);

        $invoice = Invoice::updateOrCreate(
            ['BookingID' => $booking->BookingID],
            [
                'InvoiceCode'   => 'HD-' . date('Ymd') . '-' . str_pad($booking->BookingID, 4, '0', STR_PAD_LEFT),
                'RoomAmount'    => $roomAmount,
                'ServiceAmount' => $serviceAmount,
                'TotalAmount'   => $totalAmount,
                'PaymentMethod' => $request->input('PaymentMethod'),
                'PaymentStatus' => $request->input('PaymentStatus'),
                'PaymentDate'   => now(),
            ]
        );

        return redirect()->route('invoices.show', $invoice->InvoiceID)->with('success', 'Thanh toán thành công! Đã tạo hóa đơn ' . $invoice->InvoiceCode);
    }

    public function addService(Request $request, Booking $booking)
    {
        $request->validate([
            'ServiceID' => 'required|exists:services,ServiceID',
            'Quantity' => 'required|integer|min:1|max:99',
        ], [
            'ServiceID.required' => 'Vui lòng chọn dịch vụ.',
            'ServiceID.exists' => 'Dịch vụ đã chọn không hợp lệ.',
            'Quantity.required' => 'Vui lòng nhập số lượng.',
            'Quantity.min' => 'Số lượng tối thiểu là 1.',
        ]);

        $service = Service::findOrFail($request->input('ServiceID'));
        $quantity = (int) $request->input('Quantity');

        $this->attachService($booking, $service, $quantity);

        return back()->with('success', 'Đã thêm dịch vụ ' . $service->ServiceName . ' (x' . $quantity . ') vào đơn đặt phòng');
    }

    public function removeService(Booking $booking, $bookingServiceId)
    {
        DB::table('booking_services')->where('BookingServiceID', $bookingServiceId)->delete();

        return back()->with('success', 'Đã hủy dịch vụ khỏi đơn đặt phòng');
    }

    private function attachService(Booking $booking, Service $service, $quantity)
    {
        $booking->services()->attach($service->ServiceID, [
            'Quantity' => $quantity,
            'Price' => $service->Price,
            'TotalPrice' => $service->Price * $quantity,
        ]);
    }

    private function createInvoice(Booking $booking)
    {
        $nights = max(1, $booking->nights() ?: 1);
        $roomAmount = $nights * $booking->room->RoomPrice;
        $serviceAmount = (int) $booking->services()->sum('TotalPrice');
        $totalAmount = $roomAmount + $serviceAmount;

        Invoice::updateOrCreate(
            ['BookingID' => $booking->BookingID],
            [
                'InvoiceCode'   => 'HD-' . date('Ymd') . '-' . str_pad($booking->BookingID, 4, '0', STR_PAD_LEFT),
                'RoomAmount'    => $roomAmount,
                'ServiceAmount' => $serviceAmount,
                'TotalAmount'   => $totalAmount,
                'PaymentMethod' => 'tien_mat',
                'PaymentStatus' => '1',
                'PaymentDate'   => now(),
            ]
        );
    }

    private function syncRoomStatus($roomId)
    {
        Room::syncStatus($roomId);
    }
}
