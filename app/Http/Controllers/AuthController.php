<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Mail\NewRegistrationMail;
use App\Mail\WelcomeMail;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showRegister()
    {
        if (Auth::check())
            return redirect('/course');
        return view('register');
    }

    public function register(Request $request)
    {
        // ✅ تحقق إن الدفع اتعمل
        if (!session('payment_paid')) {
            return redirect()->route('register')
                ->with('payment_error', '⚠️ يجب إتمام الدفع أولاً قبل إنشاء الحساب');
        }

        // ✅ جيب بيانات المستخدم من الـ session
        $regData = session('reg_data');

        if (!$regData || empty($regData['email'])) {
            return redirect()->route('register')
                ->with('payment_error', '⚠️ انتهت الجلسة، يرجى البدء من جديد');
        }

        // ✅ تحقق من username و password فقط
        $request->validate([
            'username' => 'required|min:3|unique:users|alpha_num',
            'password' => 'required|min:6|confirmed',
        ], [
            'username.required' => 'اسم المستخدم مطلوب',
            'username.unique' => 'اسم المستخدم مأخوذ، اختر اسم آخر',
            'username.alpha_num' => 'اسم المستخدم يجب أن يحتوي على أحرف وأرقام فقط',
            'username.min' => 'اسم المستخدم يجب أن يكون 3 أحرف على الأقل',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
            'password.confirmed' => 'كلمتا المرور غير متطابقتين',
        ]);

        // ✅ تحقق إن الإيميل مش مسجّل
        if (User::where('email', $regData['email'])->exists()) {
            session()->forget(['reg_data', 'payment_paid', 'kashier_order_id']);
            return redirect()->route('login')
                ->withErrors(['login' => 'البريد الإلكتروني مسجّل بالفعل، سجّل دخولك']);
        }

        $plainPassword = $request->password;

        // ✅ إنشاء المستخدم
        $user = User::create([
            'name' => $regData['name'],
            'email' => $regData['email'],
            'phone' => $regData['phone'],
            'username' => $request->username,
            'password' => Hash::make($plainPassword),
            'payment_method' => 'kashier',
            'role' => 'student',
        ]);

        // ✅ تسجيل في الكورس تلقائياً
        $course = Course::where('is_active', true)->first();
        if ($course) {
            Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'payment_method' => 'kashier',
            ]);
        }

        // ✅ إرسال إيميل ترحيب
        try {
            Mail::to($user->email)->send(new WelcomeMail($user, $plainPassword));
        } catch (\Exception $e) {
            \Log::warning('Welcome email failed: ' . $e->getMessage());
        }

        // ✅ إرسال إشعار للأدمن
        try {
            $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL', 'admin@divhive.com'));
            Mail::to($adminEmail)->send(new NewRegistrationMail($user));
        } catch (\Exception $e) {
            \Log::warning('Admin notification email failed: ' . $e->getMessage());
        }

        // ✅ امسح الـ session
        session()->forget(['reg_data', 'payment_paid', 'kashier_order_id']);

        Auth::login($user);

        return redirect('/course')
            ->with('welcome', 'أهلاً بك ' . $user->name . '! 🎉 تم إرسال بيانات حسابك على بريدك الإلكتروني');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect('/admin/dashboard')
                : redirect('/course');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ], [
            'login.required' => 'اسم المستخدم أو البريد مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        $login = $request->login;

        $user = User::where('username', $login)
            ->orWhere('email', $login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['login' => 'اسم المستخدم أو كلمة المرور غير صحيحة'])
                ->withInput(['login' => $login]);
        }

        Auth::login($user, $request->boolean('remember'));

        return $user->isAdmin()
            ? redirect('/admin/dashboard')
            : redirect('/course');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}