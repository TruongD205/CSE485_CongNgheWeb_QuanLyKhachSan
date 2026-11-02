<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý khách sạn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    @if (!request()->routeIs('login', 'register', 'logout.confirm'))
    @if (Session::get('AccountID'))
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('rooms.index') }}"><b>🏨 Quản Lý Khách Sạn</b></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('rooms.*') ? 'active text-warning fw-bold' : '' }}" href="{{ route('rooms.index') }}">Phòng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('bookings.*') ? 'active text-warning fw-bold' : '' }}" href="{{ route('bookings.index') }}">Đặt phòng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('services.*') ? 'active text-warning fw-bold' : '' }}" href="{{ route('services.index') }}">Dịch vụ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('invoices.*') ? 'active text-warning fw-bold' : '' }}" href="{{ route('invoices.index') }}">Hóa đơn</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="text-light me-3">Xin chào, <b>{{ Session::get('Username') }}</b></span>
                    <a href="{{ route('logout.confirm') }}" class="btn btn-outline-danger btn-sm">Đăng xuất</a>
                </div>
            </div>
        </div>
    </nav>
    @endif
    @endif

    <div class="container mt-4 mb-5">
        @yield('content')
    </div>
</body>

</html>
