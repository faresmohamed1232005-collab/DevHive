<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private function merchantId(): string
    {
        return config('services.kashier.merchant_id');
    }

    private function apiKey(): string
    {
        return config('services.kashier.api_key');
    }

    private function secretKey(): string
    {
        return config('services.kashier.secret_key');
    }

    private function mode(): string
    {
        return config('services.kashier.mode', 'test');
    }

    private int $price = 499;
    private string $currency = 'EGP';

    // ═══════════════════════════════════════════════════
    // POST /payment/session
    // يحفظ بيانات الخطوة 1 وينشئ Payment Session في كاشير
    // ═══════════════════════════════════════════════════
    public function saveSession(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'phone' => 'required',
            'email' => 'required|email',
        ]);

        if (\App\Models\User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني مسجّل بالفعل — سجّل دخولك',
            ], 422);
        }

        $sessionData = $this->createKashierSession($request->email);

        // log session data safely
        Log::info('Kashier session data', ['data' => $sessionData]);

        if (!$sessionData) {
            return response()->json([
                'success' => false,
                'message' => 'حصل مشكلة في إنشاء جلسة الدفع — تأكد من إعدادات كاشير',
            ], 500);
        }

        session([
            'payment_url' => $sessionData['sessionUrl'],
            'reg_data' => [
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
            ]
        ]);

        Log::info('Kashier session created', ['sessionUrl' => $sessionData['sessionUrl']]);

        return response()->json([
            'success' => true,
            'paymentUrl' => $sessionData['sessionUrl'],
        ]);
    }

    // ═══════════════════════════════════════════════════
    // إنشاء Payment Session عبر Kashier API
    // ═══════════════════════════════════════════════════
    private function createKashierSession(string $email): ?array
    {
        $orderId = 'ORD-' . time() . '-' . rand(1000, 9999);

        session(['kashier_order_id' => $orderId]);

        $apiUrl = $this->mode() === 'live'
            ? 'https://api.kashier.io/v3/payment/sessions'
            : 'https://test-api.kashier.io/v3/payment/sessions';

        $body = [
            'merchantId'    => $this->merchantId(),
            'amount'        => (string) $this->price,
            'currency'      => $this->currency,
            'order'         => $orderId,
            'paymentType'   => 'credit',
            'type'          => 'one-time',
            'display'       => 'ar',
            'brandColor'    => '#7c3aed',
            'allowedMethods' => 'card,wallet',
            'enable3DS'     => true,
            'merchantRedirect' => url('/payment/success'),
            'failureRedirect'  => true,
            'serverWebhook'    => url('/payment/webhook'),
            'description'      => 'اشتراك DevHive',
            'expireAt'         => now()->addHours(2)->toIso8601String(),
            'maxFailureAttempts' => 3,
            'customer' => [
                'email'     => $email,
                'reference' => $orderId,
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->secretKey(),
                'api-key'       => $this->apiKey(),
                'Content-Type'  => 'application/json',
            ])->post($apiUrl, $body);

            Log::info('Kashier API response', [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);

            if (!$response->successful()) {
                Log::error('Kashier API error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();

            // الـ sessionUrl بيجي في الـ response مباشرة
            $sessionUrl = $data['sessionUrl'] ?? null;
            $sessionId  = $data['_id'] ?? null;

            if (!$sessionUrl) {
                Log::error('Kashier: no sessionUrl in response', $data);
                return null;
            }

            session(['kashier_session_id' => $sessionId]);

            return [
                'sessionUrl' => $sessionUrl,
                'sessionId'  => $sessionId,
            ];
        } catch (\Exception $e) {
            Log::error('Kashier API exception', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    // ═══════════════════════════════════════════════════
    // GET /payment/success — كاشير بيرجع هنا بعد الدفع
    // ═══════════════════════════════════════════════════
    public function success(Request $request)
    {
        Log::info('Kashier success callback', $request->all());

        // تحقق من حالة الدفع عن طريق الـ Session API
        $sessionId = session('kashier_session_id');

        if ($sessionId) {
            $verified = $this->verifyPaymentSession($sessionId);
            if (!$verified) {
                Log::warning('Kashier: payment session not verified', [
                    'sessionId' => $sessionId,
                ]);
                if ($this->mode() === 'live') {
                    return redirect()->route('register')
                        ->with('payment_error', 'فشل التحقق من الدفع');
                }
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
        session()->forget(['payment_paid', 'kashier_order_id', 'kashier_session_id', 'payment_url']);
        return redirect()->route('register')
            ->with('payment_error', 'فشل الدفع! يرجى المحاولة مرة أخرى.');
    }

    // ═══════════════════════════════════════════════════
    // POST /payment/webhook — Webhook server-to-server
    // ═══════════════════════════════════════════════════
    public function webhook(Request $request)
    {
        Log::info('Kashier webhook', $request->all());

        $data = $request->all();
        $sessionId = $data['sessionId'] ?? $data['_id'] ?? null;
        $status = $data['status'] ?? null;

        if ($sessionId && $status === 'PAID') {
            Log::info('Kashier webhook: payment confirmed', [
                'sessionId' => $sessionId,
                'status' => $status,
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    // ═══════════════════════════════════════════════════
    // التحقق من حالة الـ Session عبر الـ API
    // ═══════════════════════════════════════════════════
    private function verifyPaymentSession(string $sessionId): bool
    {
        $apiUrl = $this->mode() === 'live'
            ? "https://api.kashier.io/v3/payment/sessions/{$sessionId}/payment"
            : "https://test-api.kashier.io/v3/payment/sessions/{$sessionId}/payment";

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->secretKey(),
                'api-key'       => $this->apiKey(),
            ])->get($apiUrl);

            if (!$response->successful()) {
                return false;
            }

            $data = $response->json();
            $status = $data['data']['status'] ?? '';

            Log::info('Kashier session verification', [
                'sessionId' => $sessionId,
                'status'    => $status,
            ]);

            return in_array($status, ['PAID', 'PENDING']);
        } catch (\Exception $e) {
            Log::error('Kashier verify exception', ['message' => $e->getMessage()]);
            return false;
        }
    }
}
