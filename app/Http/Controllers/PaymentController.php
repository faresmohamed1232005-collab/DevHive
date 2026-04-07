<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // ═══════════════════════════════════════════════════
    // ✅ بيانات كاشير — حطهم في .env
    // ═══════════════════════════════════════════════════
    private function merchantId(): string
    {
        return env('KASHIER_MERCHANT_ID', 'MID-39229-146');
    }
    private function secretKey(): string
    {
        return env('KASHIER_SECRET_KEY', 'e6488dd989d7e0e68165d97bd3955d6f$208d691717993279b364489ce314e3ab4cb21c5df43bc7166cd9fff82d48ccc9cfd1ed10802a7609ab1d62a5f3ccb5c4');
    }
    private function mode(): string
    {
        return env('KASHIER_MODE', 'test');
    } // test أو live
    private int $price = 499;
    private string $currency = 'EGP';

    // ═══════════════════════════════════════════════════
    // POST /payment/session
    // يحفظ بيانات الخطوة 1 ويرجع رابط كاشير
    // ═══════════════════════════════════════════════════
    public function saveSession(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'phone' => 'required',
            'email' => 'required|email',
        ]);

        // تحقق إن الإيميل مش مسجّل
        if (\App\Models\User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني مسجّل بالفعل — سجّل دخولك',
            ], 422);
        }

        // احفظ البيانات في الـ session
        session([
            'reg_data' => [
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
            ]
        ]);

        // أنشئ رابط الدفع
        $paymentUrl = $this->buildKashierUrl();

        Log::info('Kashier payment URL generated', ['url' => $paymentUrl]);

        return response()->json([
            'success' => true,
            'paymentUrl' => $paymentUrl,
        ]);
    }

    // ═══════════════════════════════════════════════════
    // بناء رابط كاشير مع الـ Hash
    // ═══════════════════════════════════════════════════
    private function buildKashierUrl(): string
    {
        $orderId = 'ORD-' . time() . '-' . rand(1000, 9999);
        $amount = (string) $this->price;
        $currency = $this->currency;
        $merchantId = $this->merchantId();
        $successUrl = url('/payment/success');
        $failUrl = url('/payment/fail');

        // احفظ الـ orderId
        session(['kashier_order_id' => $orderId]);

        // ✅ بناء الـ Hash (HMAC SHA256)
        // الصيغة: ?payment={merchantId}.{orderId}.{amount}.{currency}
        $hashData = "?payment={$merchantId}.{$orderId}.{$amount}.{$currency}";
        $hash = hash_hmac('sha256', $hashData, $this->secretKey());

        // ✅ بناء رابط كاشير
        $baseUrl = $this->mode() === 'live'
            ? 'https://checkout.kashier.io'
            : 'https://test-checkout.kashier.io';

        $params = http_build_query([
            'merchantId' => $merchantId,
            'orderId' => $orderId,
            'amount' => $amount,
            'currency' => $currency,
            'hash' => $hash,
            'mode' => $this->mode(),
            'merchantRedirect' => $successUrl,
            'failureRedirect' => $failUrl,
            'display' => 'ar',
            'brandColor' => '7c3aed',
            'allowedMethods' => 'card,wallet,bank_installments',
            'description' => 'اشتراك DivHive',
        ]);

        return $baseUrl . '/?' . $params;
    }

    // ═══════════════════════════════════════════════════
    // GET /payment/success — كاشير بيرجع هنا بعد الدفع
    // ═══════════════════════════════════════════════════
    public function success(Request $request)
    {
        Log::info('Kashier success callback', $request->all());

        // ✅ تحقق من صحة الـ Hash اللي بيبعته كاشير
        // كاشير بيبعت: orderId, amount, currency, hash في الـ query string
        $isValid = $this->verifyCallback($request);

        if (!$isValid) {
            Log::warning('Kashier: invalid hash in callback', $request->all());
            // في بيئة التيست نكمل — في الإنتاج ارفض
            if ($this->mode() === 'live') {
                return redirect()->route('register')
                    ->with('payment_error', '❌ فشل التحقق من الدفع');
            }
        }

        session(['payment_paid' => true]);
        return redirect()->route('register');
    }

    // ═══════════════════════════════════════════════════
    // GET /payment/fail
    // ═══════════════════════════════════════════════════
    public function fail(Request $request)
    {
        Log::info('Kashier fail callback', $request->all());
        session()->forget(['payment_paid', 'kashier_order_id']);
        return redirect()->route('register')
            ->with('payment_error', '❌ فشل الدفع! يرجى المحاولة مرة أخرى.');
    }

    // ═══════════════════════════════════════════════════
    // POST /payment/webhook — Webhook server-to-server
    // ═══════════════════════════════════════════════════
    public function webhook(Request $request)
    {
        Log::info('Kashier webhook', $request->all());
        return response()->json(['status' => 'ok']);
    }

    // ═══════════════════════════════════════════════════
    // التحقق من الـ Hash في الـ Callback
    // ═══════════════════════════════════════════════════
    private function verifyCallback(Request $request): bool
    {
        $receivedHash = $request->get('hash', '');
        if (!$receivedHash)
            return false;

        $orderId = $request->get('orderId', '');
        $amount = $request->get('amount', '');
        $currency = $request->get('currency', '');

        $hashData = "?payment={$this->merchantId()}.{$orderId}.{$amount}.{$currency}";
        $expectedHash = hash_hmac('sha256', $hashData, $this->secretKey());

        return hash_equals($expectedHash, $receivedHash);
    }
}