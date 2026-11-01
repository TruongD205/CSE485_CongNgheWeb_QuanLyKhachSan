<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý khách sạn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row justify-content-end mt-3">
            <div class="col-auto">
                @if (!request()->routeIs('login', 'register', 'logout.confirm'))
                @if (Session::get('AccountID'))
                <span class="text-muted me-2">{{ Session::get('Username') }}</span>
                <a href="{{ route('logout.confirm') }}" class="btn btn-outline-danger btn-sm">Đăng xuất</a>
                @endif
                @endif
            </div>
        </div>
    </div>

    <div class="container mt-5">
        @yield('content')
    </div>
</body>

</html>