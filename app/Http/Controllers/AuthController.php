<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'Username' => 'required',
            'Password' => 'required',
        ], [
            'Username.required' => 'Vui lòng nhập tên đăng nhập.',
            'Password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $account = Account::where('Username', $request->input('Username'))->first();

        if ($account && $account->Password === $request->input('Password')) {
            $request->session()->put('AccountID', $account->AccountID);
            $request->session()->put('Username', $account->Username);
            $request->session()->regenerate();

            return redirect()->route('rooms.index')->with('success', 'Đã đăng nhập');
        }

        return back()->withInput()->withErrors([
            'Password' => 'Sai tài khoản hoặc mật khẩu.'
        ]);
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'Username' => 'required|unique:accounts,Username',
            'Password' => 'required|min:6|confirmed',
            'Gmail' => 'required|email|max:255',
        ], [
            'Username.required' => 'Vui lòng nhập tên đăng nhập.',
            'Username.unique' => 'Tên đăng nhập này đã tồn tại.',
            'Password.required' => 'Vui lòng nhập mật khẩu.',
            'Password.min' => 'Mật khẩu phải có tối thiểu 6 ký tự.',
            'Password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'Gmail.required' => 'Vui lòng nhập địa chỉ Gmail.',
            'Gmail.email' => 'Địa chỉ Gmail không đúng định dạng.',
        ]);

        Account::create([
            'Username' => $request->input('Username'),
            'Password' => $request->input('Password'),
            'Gmail' => $request->input('Gmail'),
        ]);

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }

    public function logoutConfirm()
    {
        return view('auth.logout');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['AccountID', 'Username']);

        return redirect()->route('login')->with('success', 'Đã đăng xuất');
    }
}
