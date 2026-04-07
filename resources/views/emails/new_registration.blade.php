<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل جديد — DivHive</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',Arial,sans-serif; background:#0a0f1e; color:#e2e8f0; direction:rtl; }
        .wrapper { max-width:600px; margin:0 auto; padding:20px; }
        .card { background:linear-gradient(135deg,#0f1e4a,#1a0a3a); border-radius:20px; overflow:hidden; border:1px solid rgba(251,146,60,0.3); }
        .header { background:linear-gradient(135deg,#7c2d12,#ea580c); padding:36px 30px; text-align:center; }
        .header-icon { font-size:36px; margin-bottom:10px; }
        .header h1 { color:white; font-size:22px; font-weight:800; }
        .header p { color:rgba(255,255,255,0.75); font-size:13px; margin-top:6px; }
        .body { padding:32px 30px; }
        .alert-row { background:rgba(251,146,60,0.12); border:1px solid rgba(251,146,60,0.35); border-radius:12px; padding:16px 20px; margin-bottom:24px; }
        .alert-row p { color:#fdba74; font-size:14px; line-height:1.7; }
        .section-title { color:white; font-size:16px; font-weight:700; margin-bottom:14px; display:flex; align-items:center; gap:8px; }
        .info-row { display:flex; align-items:center; justify-content:space-between; padding:11px 16px; background:rgba(255,255,255,0.04); border-radius:9px; margin-bottom:7px; border:1px solid rgba(255,255,255,0.06); }
        .info-label { color:rgba(255,255,255,0.5); font-size:13px; }
        .info-value { color:white; font-weight:700; font-size:14px; }
        .info-value.highlight { color:#fb923c; }
        .info-value.payment { background:rgba(251,146,60,0.2); padding:3px 10px; border-radius:6px; color:#fdba74; }
        .divider { height:1px; background:rgba(255,255,255,0.07); margin:24px 0; }
        .action-title { color:white; font-size:15px; font-weight:700; margin-bottom:14px; }
        .action-btn { display:inline-block; padding:12px 28px; border-radius:10px; text-decoration:none; font-size:14px; font-weight:700; margin-left:10px; margin-bottom:10px; }
        .btn-whatsapp { background:linear-gradient(135deg,#25d366,#128c7e); color:white; box-shadow:0 4px 15px rgba(37,211,102,0.35); }
        .btn-dashboard { background:linear-gradient(135deg,#1a3a6b,#7c3aed); color:white; box-shadow:0 4px 15px rgba(124,58,237,0.35); }
        .time-badge { display:inline-block; background:rgba(255,255,255,0.08); padding:4px 12px; border-radius:6px; color:rgba(255,255,255,0.5); font-size:12px; margin-top:8px; }
        .footer { background:rgba(0,0,0,0.2); padding:20px 30px; text-align:center; border-top:1px solid rgba(255,255,255,0.06); }
        .footer p { color:rgba(255,255,255,0.3); font-size:12px; line-height:1.8; }
        @media(max-width:480px) {
            .body { padding:20px 16px; }
            .info-row { flex-direction:column; gap:6px; align-items:flex-start; }
        }
    </style>
</head>
<body>
<div class="wrapper">
<div class="card">

    <!-- Header -->
    <div class="header">
        <div class="header-icon">📩</div>
        <h1>تسجيل جديد بانتظار التأكيد</h1>
        <p>تم استلام طلب تسجيل جديد على منصة DivHive</p>
    </div>

    <!-- Body -->
    <div class="body">

        <!-- Alert -->
        <div class="alert-row">
            <p>
                ⚠️ <strong>تنبيه:</strong> مستخدم جديد سجّل في المنصة ولم يتم تأكيد دفعه بعد.
                يُرجى التواصل معه في أقرب وقت لإتمام عملية الدفع وتفعيل اشتراكه.
            </p>
        </div>

        <!-- بيانات المستخدم -->
        <div class="section-title">👤 بيانات المستخدم</div>

        <div class="info-row">
            <span class="info-label">الاسم</span>
            <span class="info-value">{{ $user->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">البريد الإلكتروني</span>
            <span class="info-value highlight">{{ $user->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">رقم الهاتف</span>
            <span class="info-value highlight">{{ $user->phone ?? '—' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">اسم المستخدم</span>
            <span class="info-value">@{{ $user->username }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">طريقة الدفع المختارة</span>
            <span class="info-value payment">{{ $user->payment_method }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">حالة الدفع</span>
            <span class="info-value" style="color:#f87171">⏳ لم يتأكد بعد</span>
        </div>

        <div class="time-badge">
            🕐 وقت التسجيل: {{ $user->created_at->format('d/m/Y — h:i A') }}
        </div>

        <div class="divider"></div>

        <!-- إجراءات -->
        <div class="action-title">📋 الإجراءات المطلوبة:</div>

        @if($user->phone)
        <a href="https://wa.me/2{{ preg_replace('/^0/', '', $user->phone) }}?text={{ urlencode('أهلاً ' . $user->name . '، نتواصل معك من منصة DivHive بخصوص إتمام عملية الدفع.') }}"
           class="action-btn btn-whatsapp">
            📱 تواصل عبر واتساب
        </a>
        @endif

        <a href="{{ config('app.url') }}/admin/dashboard"
           class="action-btn btn-dashboard">
            🖥️ لوحة التحكم
        </a>

    </div>

    <!-- Footer -->
    <div class="footer">
        <p>
            هذا إشعار تلقائي من منصة DivHive عند تسجيل مستخدم جديد.<br>
            © {{ date('Y') }} DivHive — لوحة التحكم
        </p>
    </div>

</div>
</div>
</body>
</html>